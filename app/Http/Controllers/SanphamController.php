<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Sanphams;
class SanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listsp = Sanphams::all();
        return response()->json($listsp);
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
    public function store(Request $request)
    {
        if($request->session()->has('danhsach')){
         
            $id_insert= DB::table('khachhang')->insertGetId($request->all());
            if($id_insert>0){
               
               $cart = array();
               foreach($request->session()->get('danhsach') as $row){
                    $data_cart = array();
                    $data_cart['idkh'] = $id_insert;
                    $data_cart['masp'] = $row['masp'];
                    $data_cart['soluong'] = $row['slmua'];
                    $data_cart['price'] = $row['price'];
                    array_push($cart, $data_cart);
                }
                DB::table('donhang')->insert($cart);
                $request->session()->forget('danhsach');//xóa danh sách mua
                return Response()->json(array("success"=>$id_insert,"data_cart"=>$cart));

            }
            else{
                return Response()->json(array("success"=>0));
            }
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    //them sản phẩm
    public function cart(Request $request){
        $dsmua = array();
        foreach($request->all() as $row){
            $sanpham = array();
            $sanpham['id']=$row['id'];
            $sanpham['title']=$row['title'];
            $sanpham['image']=$row['image'];
            $sanpham['price']=$row['price'];
            $sanpham['slmua']=$row['slmua'];
            $sanpham['masp']=$row['masp'];
            array_push($dsmua,$sanpham);
        }
        $request->session()->forget('danhsach');
        $request->session()->put('danhsach',$dsmua);
        return response()->json($dsmua);
    }
    public function cart_list(Request $request){
        if($request->session()->has("danhsach")){
            //echo response()->json($request->session()->get("danhsach"));
            
            return json_encode($request->session()->get("danhsach"));
        }
       
       
    }
}
