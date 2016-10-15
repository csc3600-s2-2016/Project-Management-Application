<style>

</style>
<template>
    <div>
        <h4>Assign Users</h4>
        <table class="assignUsersPanel">
        <tr>
            <th>
                <span class="h5">Unassigned</span>
            </th><th></th><th></th>
            <th>
                <span class="h5">Assigned</span>
            </th>
        </tr>
        <tr>

            <td id="unassigned" :class="unassignedClass"  v-el:unassigned-container >
                <div v-for="uid in unassigned"  id="{{uid}}">{{users[uid].displayName }}</div>
            </td>


            <td class="right-border-this"></td><td></td>


            <td id="assigned" :class="assignedClass" v-el:assigned-container>
                <div v-for="uid in assigned" id="{{uid}}">{{users[uid].displayName}}</div>
            </td>


        </tr>
        </table>
    <div>     
</template>

<script>
export default {
    props: ["users", "unassigned", "assigned"],
    data () {
        return {
            assignedClass: "notDraggingClass",
            unassignedClass: "notDraggingClass"
        }
    },
    methods: {
    },
    events: {
        'refresh' : function(refreshRequired){
            if (refreshRequired){
            }
        }
    },
    ready: function(){
        var that = this;
        Sortable = require('sortablejs');
        try{
        Sortable.create(this.$els.assignedContainer, {
            group: {name:'assigned', put:['unassigned']},
            animation: 250,
            onStart: function(){   //display drop zone backgroud
                that.unassignedClass = "dragToHereClass";
            },
            onEnd: function(){      //hide drop zone backgroud
                that.unassignedClass = "notDraggingClass"
            },
            onRemove: function(event){
                var uid = event.item.id;
                that.unassigned.push(uid);
                that.assigned.splice(that.assigned.indexOf(uid), 1);
            },
            onAdd: function(event){
                var uid = event.item.id;
                that.assigned.push(uid);
                that.unassigned.splice(that.unassigned.indexOf(uid), 1);

            }
        });
        Sortable.create(this.$els.unassignedContainer, 
            {
                group: {name: 'unassigned', put: ['assigned']},
                animation: 250,
                onStart: function(){   //display drop zone backgroud
                that.assignedClass = "dragToHereClass";
                },
                onEnd: function(){      //hide drop zone backgroud
                    that.assignedClass = "notDraggingClass"
                }
            }
        );
    }catch(err){

    }
    }
}

</script>