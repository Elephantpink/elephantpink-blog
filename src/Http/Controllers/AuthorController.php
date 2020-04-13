<?php

namespace EPink\Blog\Http\Controllers;

use EPink\Blog\Http\Requests\StoreAuthor;
use EPink\Blog\Models\Author;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Token;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            return response()->json(['authors' => Author::all()], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreAuthor  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuthor $request)
    {
        $validated = $request->validated();

        try {

            $validated['password']  = bcrypt($validated['password']);

            $author = Author::create($validated);

            return response()->json(['author' => $author], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        try {
            return response()->json(['author' => $author], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreAuthor  $request
     * @param  Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAuthor $request, Author $author)
    {
        $validated = $request->validated();

        try {

            $user_request = $request->user();

            if (!$user_request->is_admin && $user_request->id === $validated['id'] || $user_request->is_admin) {
                if (array_key_exists('password', $validated) && strlen($validated['password'])) {
                    if ((!$user_request->is_admin && $user_request->id === $validated['id']) || ($user_request->is_admin && array_key_exists('current_password', $validated))) {
                        if (!Hash::check($validated['current_password'], $author->password)) {
                            return response()->json(['errors' => ['current_password' => ['Current password is invalid']]], 400);
                        } else {
                            $validated['password'] = bcrypt($validated['password']);
                        }
                    } else if ($user_request->is_admin) {
                        $validated['password'] = bcrypt($validated['password']);
                    } else {
                        unset($validated['password']);
                    }   
                } else {
                    unset($validated['password']);
                }
                
                $author->update($validated);

                if ($author->is_disabled) {
                    Token::where('user_id', $author->id)->where('revoked', false)->update([
                        'revoked' => true
                    ]);
                }
        
                return response()->json(['author' => $author], 200);
            } else {
                return response()->json(['message' => 'Only admin users can edit other users'], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        try {
            
            $author->delete();

            return response()->json(null, 204);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected server error'], 500);
        }
    }
}
