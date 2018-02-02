<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;

class NotesController extends Controller
{
    public function getNotesList(){
        $author=auth()->user()->email;
        //获取所有当前用户的笔记
        $unorderList=Note::where('author',$author)->get();
        //初始化要输出的结果
        $result=[
            'total'=>0,
            'cate_num'=>0,
            'category'=>[],
            'notes_list'=>[]
        ];
        //对要添加的笔记列表进行遍历，以分类后输出
        foreach($unorderList as $n){
            $result['total']++;
            //若遍历到的笔记的目录在result中不存在，在结果中新增目录并添加
            if(!in_array($n['category'],$result['category']))
            {
                //目录数加一
                $result['cate_num']++;
                array_push($result['category'],$n['category']);
                array_push($result['notes_list'],[
                    'title'=>$n['category'],
                    'notes'=>array([
                        'uuid'=>$n['uuid'],
                        'title'=>$n['title'],
                        'created_at'=>$n['created_at']
                    ])
                ]);
            }else{
                //遍历到的笔记目录在结果已经存在
                foreach($result['notes_list'] as &$list){
                    if($list['title']==$n['category']){
                        array_push($list['notes'],[
                            'uuid'=>$n['uuid'],
                            'title'=>$n['title'],
                            'created_at'=>$n['created_at']
                        ]);
                    }
                }
                //解除在foreach中对子元素list的引用
                unset($list);
            }
        }
        return response()->json($result);
    }

    public function getSingleNote($uuid)
    {
        $note=Note::where('uuid',$uuid)->first();
        //return json_encode($note);
        return response()->json(array(
            'title'=>$note['title'],
            'uuid'=>$note['uuid'],
            'author'=>$note['author'],
            'created_at'=>$note['created_at'],
            'category'=>$note['category'],
            'content'=>$note['content']
        ));
    }

    public function updateSingleNote(Request $request,$uuid)
    {
        $note=Note::where('uuid',$uuid)->first();
        $note->content=$request->content;
        if($note->save())
        {
            return response()->json(array(
                'status'=>'SUCCESS',
                'msg'=>'Successfully update a note.'
            ));
        }else{
            return response()->json(array(
                'status'=>'ERROR',
                'msg'=>'Failed to update.'
            ));
        }
    }
}