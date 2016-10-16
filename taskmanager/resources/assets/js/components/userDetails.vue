<template>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group label-floating ">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" v-model="email" id="email-address" title="Email Address" placeholder="Email Address"/>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group label-floating ">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" v-model="username" id="username" title="Username" placeholder="Username"/>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <button type="button" class="btn btn-primary" v-on:click="submitNewUsername">update</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group label-floating ">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" id="new-password" title="Enter new password" placeholder="Password"/>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group label-floating ">
                <label class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" v-model="passwordTwo" id="new-password-confirm" title="Confirm New Password" placeholder="password"/>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <button type="button" class="btn btn-primary" v-on:click="submitNewPassword">Update</button>
            </div>
        </div>
    </div>
</template>
<style>
    .form-group {
        margin: 0px;
    }
</style>
<script>
    export default{
        props: {
            userId: {type: Number, default: 0}
        },
        data(){
              return{
                  email: "",
                  username: "",
                  passwordOne: "",
                  passwordTwo: ""
              };
        },
        methods: {
          getUsername(){
            this.$http.get('/user/get', {data : {"userId" : 1}})
                .then((response) => {
                    var data = response.data;
                    this.username = data.username;
                    this.email = data.email;
                }, (response) => {
                    console.log(response);
                });
          },
          submitNewPassword(){
              this.$http.post(
                      "/user/{uid}/updatePassword",
                      {data : {}
              }).then((response) => {
                  //on success
              }, (response) => {
                  // on failure
              });
          },
          submitNewUsername(){
              //update the users username
          }
        },
        created() {
            console.log("On create called...");
            this.getUsername();
            //when component created ask the server for the magical data
        }
        // on load retrieve user data
    }
</script>
