<template>
    <div class="row">
        <div class="col-md-4">
            <h4>Total Hours Contributed</h4>
            <div class="round-button">
                <div class="round-button-circle">
                    <span class="round-button"><strong>200</strong></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h4>Total Tasks Contributed To</h4>
            <div class="round-button">
                <div class="round-button-circle">
                    <span class="round-button"><strong>6</strong></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h4>Effort / Task Ratio</h4>
            <div class="round-button">
                <div class="round-button-circle">
                    <span class="round-button"><strong>33.33</strong></span>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    h4 {
        text-align: center;
    }
    .round-button {
        margin: 0px auto;
        width:6em;
        margin-bottom: 1em;
    }
    .round-button-circle {
        width: 100%;
        height:0;
        padding-bottom: 100%;
        border-radius: 50%;
        overflow:hidden;
        border-width: 0.2em;
        background: inherit;
        box-shadow: 0 0 3px gray;
    }
    .round-button span {
        display:block;
        float:left;
        width:100%;
        padding-top:50%;
        padding-bottom:50%;
        line-height:1em;
        margin-top:-0.5em;
        color:#999999;
        text-align:center;
        font-size:1.2em;
        text-decoration:none;
    }
</style>
<script>
    export default{
        data(){
            return{
                msg:'hello vue'
            }
        }
    }
</script>
