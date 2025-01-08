<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RemoveBackgroundController extends Controller {
    public function Index() {
        return view('remove_background.index');
    }

    public function RemoveBackground(Request $request): \Illuminate\Http\JsonResponse {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = 'image_origin.' . $image->getClientOriginalExtension();
            $path =  env('DIR_IMAGE_REMOVE_BACKGROUND') . '/' . $image->getClientOriginalName() . '_' . time() . '_' . rand(11111, 99999999);
            $nameFileRemoved = $path . '/image_removed_background.' . $image->getClientOriginalExtension();
            $image->storeAs('public/' . $path, $fileName);
            $imageRemovedBackground = $this->removeBackgroundApi($image->getRealPath(), $fileName, $nameFileRemoved);

            return response()->json([
                'success' => true,
                'image_removed_background' => $imageRemovedBackground,
                'image_origin' => asset('storage/' . $path . '/' . $fileName)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found'
        ], 400);
    }

    private function removeBackgroundApi($path, $fileName, $nameFileRemoved): String {
        $client = new Client();
        $response = $client->post(env('PHOTOROOM_ENDPOINT_API'), [
            'headers' => [
                'Accept' => 'image/png, application/json',
                'x-api-key' => env('PHOTOROOM_API_KEY'),
            ],
            'multipart' => [
                [
                    'name'     => 'image_file',
                    'contents' => fopen($path, 'r'),
                    'filename' => $fileName
                ],
            ]
        ]);

        $resultImage = $response->getBody();
        $filePath = storage_path('app/public/' . $nameFileRemoved);
        file_put_contents($filePath, $resultImage);

        return url('storage/' . $nameFileRemoved);
    }
}
