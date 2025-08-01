<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:png,jpg,jpeg,pdf|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $file = $request->file('file');

            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post(env('EXTERNAL_UPLOAD_URL') .'/upload');

            if ($response->successful()) {
                $uploadedFileName = trim($response->body());

                \Log::info('External API Response:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'uploaded_filename' => $uploadedFileName
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'data' => [
                        'uploaded_filename' => $uploadedFileName,
                        'original_filename' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload file to external server',
                    'error' => $response->body()
                ], $response->status());
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during file upload',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'filename' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $filename = $request->input('filename');

            $response = Http::post(env('EXTERNAL_UPLOAD_URL') . '/delete_image', [
                'inp' => $filename
            ]);

            if ($response->successful()) {
                $responseBody = trim($response->body());

                \Log::info('External Delete API Response:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'filename' => $filename
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully',
                    'data' => [
                        'filename' => $filename,
                        'response' => $responseBody
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete file from external server',
                    'error' => $response->body()
                ], $response->status());
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during file deletion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
