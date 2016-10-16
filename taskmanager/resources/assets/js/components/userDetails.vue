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
                <button type="button" class="btn btn-primary" v-on:click="submitNewUsernameEmail()">Update</button>
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
                <button type="button" class="btn btn-primary" v-on:click="submitNewPassword()">Update</button>
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
            userId: {type: Number, default: 0},
            username: {type: String, default: ""},
            email: {type: String, default: ""}
        },
        data(){
              return{
                  passwordOne: "",
                  passwordTwo: ""
              };
        },
        methods: {


          submitNewPassword(){
              var dataPackage = {"updatePassword" : true, "passwordOne": this.passwordOne, "passwordTwo": this.passwordTwo};
              this.$http.post("/profile", dataPackage, {'headers': {'Content-Type': 'application/json'}}).then((response) => {
                  this.passwordOne = "";
                  this.passwordTwo = "";
                  console.log(response);
              }, (response) => {
                  // on failure
              });
          },
          submitNewUsernameEmail(){
              var dataPackage = {"updateIdentity": true, "username": this.username, "email": this.email};
              this.$http.post("/profile", dataPackage, {'headers': {'Content-Type': 'application/json'}}).then((response) => {
                  console.log(response);
              }, (response) => {
                  // on failure
              });
          }
        }
        // on load retrieve user data
    }
</script>
