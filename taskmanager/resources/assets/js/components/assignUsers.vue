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

            <td id="unassigned" :class="unassignedClass"  v-el:unassigned-container>
                <div v-for="user in unassigned"  id="{{user.id}}">{{user.displayName}}</div>
            </td>


            <td class="right-border-this"></td><td></td>


            <td id="assigned" :class="assignedClass" v-el:assigned-container>
                <div v-for="user in assigned" id="{{user.id}}">{{user.displayName}}</div>
            </td>


        </tr>
        </table>
    <div>     
</template>

<script>
export default {
    props: ["users", "assigned"],
    data () {
        return {
            unassigned: {},
            assignedClass: "notDraggingClass",
            unassignedClass: "notDraggingClass"
        }
    },
    methods: {
    },
    events: {
        'refresh' : function(refreshRequired){
            if (refreshRequired){
                this.assigned = {};
                this.unassigned = jQuery.extend({}, this.users);
            }
        }
    },
    ready: function(){
        var that = this;
        this.unassigned = jQuery.extend({}, this.users); //copy all users into unassigned
        Sortable = require('sortablejs');
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
                var item = event.item;
                that.unassigned[item.id] = {'id': item.id, 'displayName':item.innerText};
                delete that.assigned[item.id];
            },
            onAdd: function(event){
                var item = event.item;
                that.assigned[item.id] = {'id': item.id, 'displayName':item.innerText};
                delete that.unassigned[item.id];
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
    }
}

</script>