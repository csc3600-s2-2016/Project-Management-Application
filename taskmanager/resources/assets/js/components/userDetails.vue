<template>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group label-floating ">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="email-address" title="Email Address" placeholder="Email Address"/>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group label-floating ">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="username" title="Username" placeholder="Username"/>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <button type="button" class="btn btn-primary">update</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group label-floating ">
                <label class="form-label">New Password</label>
                <input type="email" class="form-control" id="new-password" title="Email Address" placeholder="Email Address"/>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group label-floating ">
                <label class="form-label">Confirm New Password</label>
                <input type="text" class="form-control" id="new-password-confirm" title="Confirm New Password" placeholder="password"/>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <button type="button" class="btn btn-primary">update</button>
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
        methods: {
          submitNewPassword(){
              //ajax call to grab new password
          },
        }
        // on load retrieve user data
    }
</script>
