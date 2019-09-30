<?php

namespace App\Repositories;

use App\Model\{Note, Tag};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Cache,Validator};
use App\Repositories\Interfaces\NoteInterface;


class NoteRepository implements NoteInterface
{
    /**
     * @param int $id
     * @return Note
     */
    public function findById(int $id)
    {
        try{
            if(config('app.cache_is_active'))
            {
                $note =  Cache::remember('note_'.$id, 30, function() use ($id){
                    return Note::with('tags')->find($id);
                });
            }else{
                $note = Note::with('tags')->find($id);
            }

            if (! $note) {
                return response()->json(['statu' => false, 'message' => 'No result']);
            }

            return $note;
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()]);
        }
    }

    /**
     * @return Collection
     */
    public function getAll($page = false)
    {
        try{
            if(config('app.cache_is_active')){
                $notes = Cache::remember('notes'.($page) ? $page : '', 30, function() use($page){
                    $collection =  Note::with('tags');
                    return ($page) ? $collection->paginate(30) : $collection->get();
                });
            }else{
                $notes =  Note::with('tags');
                $notes = ($page) ? $notes->paginate(30) : $notes->get();
            }

            if($notes->count() == 0)
            {
                return response()->json(['statu' => false, 'message' => 'No result']);
            }

            return $notes;
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()]);
        }


    }

    /**
     * @return Tag
     */
    public function create()
    {
        try{
            if(config('app.cache_is_active')){
                return Cache::remember('tags', 30, function(){
                    return Tag::all();
                });
            }else{
                return Tag::all();
            }
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()]);
        }


    }

    /**
     * @param array $request
     * @return mixed
     */
    public function store(array $request)
    {
        $validator = Validator::make($request,[
            'name'      => 'bail|required|string|min:3|max:50',
            'content'   => 'required',
            'tags.*'    => 'required|numeric|min:1',
        ],[
            'name.required'     => 'name alanının doldurulması gerekmektedir.',
            'name.min'          => 'name alanı en az :min karakter olmalıdır.',
            'name.max'          => 'name alanı en az :max karakter olmalıdır.',
            'content.required'  => 'content alanının doldurulması gerekmektedir.',
            'tags.*.required'   => 'tag alanında en az :min adet data olması gerekmektedir.',
            'tags.*.min'        => 'tag alanında en az :min adet data olması gerekmektedir.'
        ]);

        if($validator->fails()){
            return response()->json($validator);
        }

        try{
            $note = Note::create([
                'name' => $request['name'],
                'content' => $request['content']
            ]);

            if (isset($request['tags']))
                $note->tags()->sync($request['tags']);

            return $note;
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()]);
        }

    }

    /**
     * @param array $request
     * @param int $id
     * @return mixed
     */
    public function update(array $request, int $id)
    {
        $validator = Validator::make($request,[
            'name'      => 'bail|required|string|min:3|max:50',
            'content'   => 'required',
            'tags.*'    => 'required|numeric|min:1',
        ],[
            'name.required'     => 'name alanının doldurulması gerekmektedir.',
            'name.min'          => 'name alanı en az :min karakter olmalıdır.',
            'name.max'          => 'name alanı en az :max karakter olmalıdır.',
            'content.required'  => 'content alanının doldurulması gerekmektedir.',
            'tags.*.required'   => 'tag alanında en az :min adet data olması gerekmektedir.',
            'tags.*.min'        => 'tag alanında en az :min adet data olması gerekmektedir.'
        ]);

        if($validator->fails()){
            return response()->json($validator);
        }

        try{
            $note = Note::with('tags')->find($id);

            if(! $note)
            {
                return response()->json(['statu' => false, 'message' => 'No result']);
            }

            $note->update([
                'name' => $request['name'],
                'content' => $request['content']
            ]);

            if (isset($request['tags']))
                $note->tags()->sync($request['tags']);

            Cache::forget('note_'.$id);
            return Cache::remember('note_'.$id, 30, function() use ($note){
                return $note;
            });
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()]);
        }

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function edit(int $id)
    {
        try{
            $note = Cache::remember('note_'.$id, 30, function() use ($id){
                return Note::with('tags')->find($id);
            });

            if(! $note)
            {
                return response()->json(['statu' => false, 'message' => 'No result']);
            }

            return [
                'note' => $note,
                'tags' => Tag::all()
            ];
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        try{
            $note = Note::find($id);

            Cache::forget('note_'.$id);

            if(! $note)
            {
                return response()->json(['statu' => false, 'message' => 'No result']);
            }

            return $note->delete();
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()]);
        }


    }
}
