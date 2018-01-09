<template>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<h5 class="label label-success">Thông tin đặt hàng</h5>
			<table class="table">
				<tr>
					<td>Họ tên</td>
					<td><input type="text" placeholder="Nhập họ tên" class="form-control" name="hoten" v-model="thongtin.hoten"></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="email" class="form-control" placeholder="Nhập email" name="email" v-model="thongtin.email"></td>
				</tr>
				<tr>
					<td>Số điện thoại</td>
					<td><input type="text" placeholder="Nhập họ số điện thoại" class="form-control" name="sodienthoai" v-model="thongtin.sodienthoai"></td>
				</tr>
				<tr>
					<td>Địa chỉ</td>
					<td><input type="text" class="form-control" placeholder="Nhập địa chỉ" name="diachi" v-model="thongtin.diachi"></td>
				</tr>
				<tr>
					<td colspan="2"><button class="btn btn-success" v-on:click="dathang">Đặt hàng</button>
						<label class="label label-primary" v-if="a_check">Đặt hàng thành công</label>
					</td>
				</tr>
			</table>
			<h5 class="label label-danger">Danh sách sản phẩm</h5>
			<table class="table">
				<thead>
					<tr>
						<th>MASP</th>
						<th>TITLE</th>
						<th>IMAGE</th>
						<th>SL</th>
						<th>PRICE</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(sp,index) in dsmua">
						<td>{{sp.masp}}</td>
						<td><label class="label label-danger">{{sp.title}}</label></td>
						<td><img :src="sp.image" class="class_img"/></td>
						<td><input type="int" class="form-control" :value="sp.slmua"/></td>
						<td><span class="label label-danger">{{sp.price}}</span></td>
					</tr>
				</tbody>
			</table>
			
		</div>
		<div class="col-md-2"></div>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				dsmua:[],
				thongtin:{},
				a_check:false
			}
		 
		},
		created:function(){
			this.load_dsmua();
		},
		methods:{
			load_dsmua(){
				this.axios.get("http://localhost:8000/cart-list").then((response)=>{
					this.dsmua=response.data;	
					console.log(this.dsmua);
				})
			},
			dathang:function(){
				this.axios.post("http://localhost:8000/sanpham",this.thongtin).then((response)=>{
					var result = response.data;
					if(parseInt(result.success)>0){
						console.log(result.success);
						this.a_check=true;
					}
					

				})
			}
		}
	}
</script>