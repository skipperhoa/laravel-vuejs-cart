<template>
	<div class="row">
		<div class="col-md-6">
			<h5 class="label label-primary">Danh sach san pham</h5>
			<table class="table">
				<thead>
					<tr>
						<th>MASP</th>
						<th>TITLE</th>
						<th>IMAGE</th>
						<th>PRICE</th>
						<th>MUA</th>
					</tr>
				</thead>
				<tbody >
					<tr v-for="(sp,index) in listsp">
						<td>{{sp.masp}}</td>
						<td>{{sp.title}}</td>
						<td align="center"><img :src="sp.image" class="class_img" /></td>
						<td><span class="label label-success">{{sp.price}}</span></td>
						<td><a href="#" class="label label-primary" v-on:click="muasp(index)">Mua</a></td>
					</tr>
				</tbody>
			</table>

		</div>
		<div class="col-md-6">
			 <h5 class="label label-success">Danh sách đã mua |Số lượng:<span>{{tongsosl}}</span></h5>
			<table class="table">
				<thead>
					<tr>
						<th>MASP</th>
						<th>TITLE</th>
						<th>SL</th>
						<th>PRICE</th>
						<th>XOA</th>
					</tr>
				</thead>
				<tbody>
					 <tr v-for="(mua, index) in dsmua">    
                        <td><label class="label label-danger">{{mua.title}}</label></td>
                         <td><input type="int" v-model="mua.slmua" class="form-control" :name="mua.masp" :data-id="mua.id"></td>
                        <td><label class="label label-danger">{{mua.price}}</label></td>
                         <td><a href="#" class="label label-info" v-on:click="editsp(index)">Cập nhật</a></td>
                        <td><a href="#" class="label label-warning" v-on:click="xoasp(index)">Xóa</a></td>
                    </tr>
                    <tr>
                        <td colspan="4" v-if="tonggia>0"><label class="label label-primary">Tổng giá:{{tonggia}}</label>
                            <router-link :to="{name:'Dathang'}"><button class="btn btn-success" >Đặt hàng</button></router-link>
                        </td>
                    </tr>
				</tbody>
			</table>
		</div>
	</div>
</template>
<script>

	export default{
		data(){
			return {
				listsp:[],
				dsmua:[],  
	            tongsosl:0,
	            tonggia:0,
			}
		},
       
		created:function(){
			this.danhsachsp();
            this.load_dsmua();
			this.giohang();  
		},
		methods:{
			danhsachsp(){
				 this.axios.get("http://localhost:8000/sanpham").then((response)=>{
				 	this.listsp=response.data;
                });
			},
			muasp:function(index){
                var sanpham = this.listsp[index];//lấy vị trí index của sản phẩm
                var items = this.dsmua; //lấy tất cả danh sách mà chúng ta đã chọn mua
                var check_sp=false;  //dùng check xem sản phẩm này có trong danh sách mua chưa true:có, false:không
                var vitri=0;//dùng lấy vị trí sản phẩm có trong danh sách mua
                var result = Object.keys(items).map(function(key) {
                   if(items[key].masp==sanpham.masp){
                       check_sp=true;
                       vitri=key; //gán vị trí đã tìm thấy sản phẩm có trong danh sách mua
                   }
    			}); 
                if(check_sp){
                    //cập nhật lại sản phẩm đã có trong danh sách mua
                    this.dsmua[vitri]={
                        "id":sanpham.id,
                        "title":sanpham.title,
                        "image":sanpham.image,
                        "price":this.listsp[index].price*parseInt(this.dsmua[vitri].slmua+1),
                        //"slmua":document.querySelector("input[name="+index_edit.masp+"]").value
                        "slmua":parseInt(this.dsmua[vitri].slmua)+1,
                        "masp":sanpham.masp
                    }
                }else{

                    //thêm sản phẩm mới vào danh sách mua
                    this.dsmua.push({
                        "id":sanpham.id,
                        "title":sanpham.title,
                        "image":sanpham.image,
                        "price":sanpham.price,
                        "slmua":1,
                        "masp":sanpham.masp
                    });
                }
               
                this.save_dsmua();//gọi hàm này dùng để gửi mảng danh sách mua qua phía laravel lưu vào mảng session
                this.giohang();	//gọi tính lại tổng số lượng sản phẩm, tổng giá		
            },
            xoasp:function(index){
                this.dsmua.splice(index,1); //xóa sản phẩm khỏi danh sách mua
                this.save_dsmua();
                this.giohang();
            },
            editsp:function(index){
               // var sanpham = this.listsp.indexOf(index);
                 var index_edit = this.dsmua[index];
                // var sl = index_edit.slmua;
                 this.dsmua[index]={
                    "id":index_edit.id,
                    "title":index_edit.title,
                    "image":index_edit.image,
                    "price":this.listsp[index].price*parseInt(this.dsmua[index].slmua),
                    //"slmua":document.querySelector("input[name="+index_edit.masp+"]").value
                    "slmua":parseInt(this.dsmua[index].slmua),
                     "masp":index_edit.masp
                }
              this.save_dsmua();
              this.giohang();
            },
            giohang(){
                var items= this.dsmua;
                var tong=0;
                var toggia=0;
                var result = Object.keys(items).map(function(key) {
                    //console.log(items[key].slmua);
    				tong=tong+parseInt(items[key].slmua);
                    toggia=toggia+parseInt(items[key].price);
                   // console.log("tong la:"+tong);
    			}); 
                this.tongsosl=tong;
                this.tonggia=toggia;
            },
            load_dsmua(){
                this.axios.get("http://localhost:8000/cart-list").then((response)=>{
                    var data2 = response.data;
                    if(data2.length>0){
                       this.dsmua=response.data;
                       this.giohang();
                    }
                    else{
                        console.log("ckhong con");
                    }
                })
            },
            save_dsmua(){
                 //dùng lưu danh sách mua vào một cái mảng session bên phía laravel
                this.axios.post("http://localhost:8000/cart",this.dsmua).then((response)=>{
                    console.log(response.data);

                });
            }
            
		}
	}
	
</script>