<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Resources\DocumentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Returns list of documents.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $pagination = $request->only(['page', 'perPage']);

        if (@$pagination['page']) {
            $pagination['page'] = (int)$pagination['page'];
        } else {
            $pagination['page'] = 1;
        }

        if (@$pagination['perPage']) {
            $pagination['perPage'] = (int)$pagination['perPage'];
        } else {
            $pagination['perPage'] = 20;
        }

        $pagination['total'] = Document::count();

        $offset = $pagination['perPage'] * ($pagination['page'] - 1);

        if ($offset >= $pagination['total'] && $pagination['page'] != 1) {
            abort(404);
        }

        return response()->json([
            'document' => DocumentResource::collection(
                Document::limit($pagination['perPage'])
                    ->offset($offset)
                    ->get()
            ),
            'pagination' => $pagination
        ]);
    }

    /**
     * Returns specified document.
     *
     * @param string $id
     * @return DocumentResource
     */
    public function show(string $id): DocumentResource
    {
        return new DocumentResource(Document::findOrFail($id));
    }

    /**
     * Creates new document and returns it.
     *
     * @return DocumentResource
     */
    public function create(): DocumentResource
    {
        $document = new Document();
        $document->save();
        return new DocumentResource($document);
    }

    /**
     * Edits document and returns it.
     *
     * @param Request $request
     * @param string $id
     * @return DocumentResource
     */
    public function edit(Request $request, string $id): DocumentResource
    {
        $document = Document::findOrFail($id);

        if ($document->isPublished()) {
            abort(400);
        }

        $data = @$request->json()->get('document')['payload'];

        if (empty($data)) {
            abort(400);
        }

        $document->mergePayload($data);
        $document->save();

        return new DocumentResource($document);
    }

    /**
     * Edits document and returns it.
     *
     * @param string $id
     * @return DocumentResource
     */
    public function publish(string $id): DocumentResource
    {
        $document = Document::findOrFail($id);
        $document->publish()->save();

        return new DocumentResource($document);
    }
}
