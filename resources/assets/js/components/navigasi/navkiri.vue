<template>

  <div class="container">
     <nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <a href="#off-canvas" class="js-offcanvas-trigger navbar-toggle collapsed" data-toggle="collapse"  data-offcanvas-trigger="off-canvas" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </a>
	    
	      <a class="navbar-brand" href="javascript:void(0)" v-on:click="colapse">Paycoll</a>
	    </div>

	    <div class="navbar-collapse" v-bind:class="{ collapse: col }" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	       <li class="btn-hover">
                <a href="javascript:void(0)" v-on:click="dashboard">Dashboard</a>
            </li>
            <li class="btn-hover" v-show="collector">
               <a href="javascript:void(0)" v-on:click="status">Status</a>
            </li>
            <li class="btn-hover" v-show="collector">
                <a href="javascript:void(0)" v-on:click="transfer">Transfer Bank</a>
            </li>
            <li class="btn-hover" v-show="toko">
	          <a href="javascript:void(0)" v-on:click="request">Request</a>
	        </li>
	        <li class="btn-hover" v-show="toko">
	          <a href="javascript:void(0)" v-on:click="profile">Profile</a>
	        </li class="btn-hover">
	        <li class="btn-hover">
	          <a href="javascript:void(0)" v-on:click="password">Change Password</a>
	        </li class="btn-hover">
            <li class="btn-hover">
               <a href="javascript:void(0)" v-on:click="logout">Logout</a>
            </li>
	      </ul>

	       
	     <!--  <ul class="nav navbar-nav navbar-right">
	        <li><a href="#">Link</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">Action</a></li>
	            <li><a href="#">Another action</a></li>
	            <li><a href="#">Something else here</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">Separated link</a></li>
	          </ul>
	        </li>
	      </ul> -->
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
  </div>
  <!--/.container    -->


		<!-- <ul class="navbar nav" v-show="collector">
            <li class="btn-hover">
                <a href="javascript:void(0)" v-on:click="dashboard">Dashboard</a>
            </li>
            <li class="btn-hover">
               <a href="javascript:void(0)" v-on:click="status">Status</a>
            </li>
            <li class="btn-hover">
                <a href="javascript:void(0)" v-on:click="transfer">Transfer Bank</a>
            </li>
            <li class="btn-hover">
               <a href="javascript:void(0)" v-on:click="logout">Logout</a>
            </li>
    	</ul>
    	<ul class="navbar nav" v-show="toko">
             <li class="btn-hover">
	          <a href="javascript:void(0)" v-on:click="dashboard">Dashboard</a>
	        </li>
	        <li class="btn-hover">
	          <a href="javascript:void(0)" v-on:click="request">Request</a>
	        </li>
	        <li class="btn-hover">
	          <a href="javascript:void(0)" v-on:click="profile">Profile</a>
	        </li class="btn-hover">
	        <li class="btn-hover">
	          <a href="javascript:void(0)" v-on:click="logout">Logout</a>
	        </li>
    	</ul>-->
</template>
<script>
	export default{
		data(){
			return{
				collector:false,
				toko:false,
				col:true
			}
		},
		created : function(){
			this.condition()
		},
		methods : {
			condition(){
				let type = this.$session.get('userinfo').typeuser
				if(type === 'collector'){
					this.collector = true
					this.toko = false
				}else if(type === 'toko'){
					this.collector = false
					this.toko = true
				}
			},
		   	logout(){
	            this.$session.destroy()
	            this.$router.push('/login')
	        },
	        status(){
	            this.$router.push('/status')
	        },
	        dashboard(){
	            this.$router.push('/dashboard')
	        },
	        request(){
	            this.$router.push('/request')
	        },
	        transfer(){
	            this.$router.push('/transfer-bank')
	        },
	        profile(){
	            this.$router.push('/toko/profile')
	        },
	        password(){
	            this.$router.push('/change-password')
	        },
	        colapse(){
	        	if(this.col == true){
	        		this.col = false	
	        	}else{
	        		this.col = true
	        	}
	        	
	        }
		}
	}
</script>