<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionCollection;
use App\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Import\SessionImport;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    use Result;

    /**
     * @api {get} /api/session 显示学期列表
     * @apiGroup session
     *
     * @apiSuccessExample 返回学期信息列表，分页显示，每页15条记录,
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  学期标识
     *       "year": 2016  //数字型 学年
     *       "team": 2  //  数字型 学期
     *       "remark": "2016-2017下学期" // 备注说明
     *       "one": 20,  // 高一年级班级数
     *       "two": 20,  // 高二年级班级数
     *       "three": 20  // 高三年级班级数
     *     }
     *   ],
     *  "status": "success",
     *  "status_code": 200,
     *  "links": {
     *  "first": "http://manger.test/api/session?page=1",
     *  "last": "http://manger.test/api/session?page=1",
     *  "prev": null,
     *  "next": null
     *  },
     *  "meta": {
     *  "current_page": 1,   //当前页
     *  "from": 1,  // 当前记录
     *  "last_page": 1,    //最后一页
     *  "path": "http://manger.test/api/session",
     *  "per_page": 15,  //
     *  "to": 4,  //当前页最后一条记录
     *  "total": 4 // 总记录
     *  }
     * }
     *
     */
    public function index()
    {
        //

        $data = Session::paginate(15);
        return new SessionCollection($data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {post}/api/session  新建一个学期信息
     * @apiGroup session
     * @apiParam {number} year 学年
     * @apiParam {number=1,2} team 学期(1=>上学期 2=>下学期)
     * @apiParam {number} one 高一班级数
     * @apiParam {number} two 高二班级数
     * @apiParam {number} three 高三班级数
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 建立学期 2017-2018上学期:
     *   {
     *      year: 2017,
     *      team: 1,
     *      one: 20,
     *      two: 20,
     *      three: 20
     * }
     *@apiHeaderExample {json} 请求头:
     *{ "Content-Type": "application/x-www-form-urlencoded" }
     *
     * @apiSuccessExample {json}  操作成功:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": "success",
     *       "status_code": 200
     *     }
     * @apiErrorExample {json} 数据验证出错:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": "error",
     *       "status_code": 404,
     *       "message": "验证出错,请按要求填写"
     *     }
     * @apiErrorExample {json} 重复提交:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": "error",
     *       "status_code": 400,
     *       "message": "你提交的学期信息已经存在，无法新建"
     *     }
     */
    public function store(Request $request)
    {
        //
        //
        $data = $request->only('year', 'team', 'remark');
        $validator = Validator::make($data, [
            'year' => 'required|integer',
            'team' => 'required| in:1,2',
            'remark' => 'nullable',
            'one' => 'required|integer',
            'two' => 'required|integer',
            'three' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->errorWithInfo('验证出错,请按要求填写');
        } else {
            if (! Session::where('year',$data['year'])->where('team', $data['team'])->count()) {
                Session::create($data);
                return $this->success();
            } else {
                return $this->errorWithCodeAndInfo(400, '你提交的学期信息已经存在，无法新建');
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {get} /api/session/:id  获取指定学期信息
     * @apiGroup session
     * @apiParam {number} id 学期标识
     * @apiSuccessExample {json}  信息获取成功:
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  学期标识
     *       "year": 2016  //数字型 学年
     *       "team": 2  //  数字型 学期
     *       "remark": "2016-2017下学期" // 备注说明
     *       "one": 20,  // 高一年级班级数
     *       "two": 20,  // 高二年级班级数
     *       "three": 20  // 高三年级班级数
     *     }
     *   ],
     *  "status": "success",
     *  "status_code": 200
     * }
     * @apiErrorExample {json} 指定的学期不能存在:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": "error",
     *       "status_code": 404
     *     }
     */
    public function show(Session $session)
    {
        //
       if ($session) {
           return  new \App\Http\Resources\Session($session);
       } else {
           return $this->error();
       }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function edit(Session $session)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {patch}/api/session/:id  更新学期信息
     * @apiGroup session
     * @apiParam {number} id 学期标识 路由上使用
     * @apiParam {number} year 学年
     * @apiParam {number=1,2} team 学期(1=>上学期 2=>下学期)
     * @apiParam {number} one 高一班级数
     * @apiParam {number} two 高二班级数
     * @apiParam {number} three 高三班级数
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 建立学期 2017-2018上学期:
     *   {
     *      year: 2017,
     *      team: 1,
     *      remark: '2017-2018上学期',
     *      one: 20,
     *      two: 20,
     *      three: 20
     *
     * }
     *@apiHeaderExample {json} 请求头:
     *{ "Content-Type": "application/x-www-form-urlencoded" }
     *
     * @apiSuccessExample {json}  操作成功:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": "success",
     *       "status_code": 200
     *     }
     * @apiErrorExample {json} 数据验证出错:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": "error",
     *       "status_code": 404,
     *       "message": "验证出错,请按要求填写"
     *     }
     */
    public function update(Request $request, Session $session)
    {
        //
        $data = $request->only('year', 'team', 'remark');
        $validator = Validator::make($data, [
            'year' => 'required|integer',
            'team' => 'required| in:1,2',
            'remark' => 'nullable',
            'one' => 'required|integer',
            'two' => 'required|integer',
            'three' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->errorWithInfo('验证出错,请重新填写');
        } else {
           $session->year = $data['year'];
           $session->team = $data['team'];
           $session->remark = $data['remark'];
           $session->one = $data['one'];
           $session->two = $data['two'];
           $session->three = $data['three'];
           $session->save();
           return $this->success();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {delete} /api/session/:id  删除指定的学期信息
     * @apiGroup session
     * @apiParam {number} id 学期标识
     * @apiSuccessExample {json}  信息获取成功:
     * HTTP/1.1 200 OK
     * {
     *  "status": "success",
     *  "status_code": 200
     * }
     * @apiErrorExample {json} 删除失败，没有指定的学期:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": "error",
     *       "status_code": 404，
     *       "message": "删除失败"
     *     }
     */

    public function destroy(Session $session)
    {
        //
        if ($session->delete()) {
            return $this->success();
        } else {
            return $this->errorWithInfo('删除失败');
        }
    }

    public function upload(SessionImport $import)
    {
        $bool = $import->handleImport($import);
        if ($bool) {
            return $this->success();
        } else {
            return $this->error();
        }


    }

    /**
 * @api {get} /api/getSession 获取学期信息
 * @apiGroup other
 *
 * @apiSuccessExample 返回学期信息列表,
 * HTTP/1.1 200 OK
 * {
 *  "data": [
 *     {
 *       "id": 2 // 整数型  学期标识
 *       "year": 2016  //数字型 学年
 *       "team": 2  //  数字型 学期
 *     }
 *   ],
 *  "status": "success",
 *  "status_code": 200
 * }
 *
 */
    public function getSession()
    {
        $data = Session::select('id','year','team')->get()->toArray();
        return $this->successWithData($data);
    }
    
}
