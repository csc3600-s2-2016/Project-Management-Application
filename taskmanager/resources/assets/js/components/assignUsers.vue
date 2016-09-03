<style>

</style>
<template>

        <h4>Assign Users</h4>
        <div class="row">
            <div class="col-sm-6 assignUsersPanel">
                <span class="h5">Unassigned</span>
                <ul v-sortable="{group :{name:'unassigned', put:['assigned']}, animation: '250'}">
                    <li>Simon</li><li>Matt</li><li>Jerry</li><li>Adelaide</li><li>Emma</li><li>Noah</li>
                </ul>
            </div>
            <div class="col-sm-6 assignUsersPanel">
            <span class="h5">Assigned</span>
                <ul v-sortable="{group :{name:'assigned', put:['unassigned']}, animation: '250'}">
                    <li>Paul</li>
                </ul>
            </div>
        </div>
            
</template>

<script>
export default {
    data () {
        return {
        	
        }
     },
     methods: {
        
     }
}

</script>