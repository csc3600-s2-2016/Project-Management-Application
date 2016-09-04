<template>
	<div :class="colClass" class="task-col-container" :style="colWidths">
		<div class="task-column-heading-container">
			<span class="h2">
				{{name}}
			</span>
		</div>
		<div class="task-column" :style="colPadding" v-sortable="sortableOptions">

				<task-card v-for="task in tasks | filterBy colID in 'status'" :task.sync="task" ></task-card>

		</div>
	</div>
</template>

<script>
import TaskCard from './taskCard.vue';
export default {
	props: [
		"name",
		"colNames",
		"tasks"
	],
	data () {
        return {
        	
        }
    },
    computed: {
        colID: function(){
            for (var i=0;i<this.colNames.length;i++){
                if (this.colNames[i] === this.name){
                    return i;
                }
            }
            return -1;
        },
    	colSize: function(){
    		if (this.colNames.length === 3){
    			return 4;
    		}
			return 3;
    	},
    	colClass: function(){
    		return "col-md-" + this.colSize;
    	},
    	colPadding: function(){
    		if (this.colNames.length === 3){
    			return "padding: 10px 50px;";
    		} else {
    			return "padding: 10px 30px;";
    		}
    	},
    	
    	sortableOptions: function(){
    		return {
    			group: {
    				name: this.name,
    				put: this.puttableColumns()
    			},
    			animation: '250'
    		}
    	},
        colWidths: function(){
            var style = "@media (min-width: 992px) {padding:0px;max-width:";
            var maxPercentage = 100 / this.colNames.length;
            style += maxPercentage + "%;}";
            return style;
        }
    },
    methods: {
    	puttableColumns: function(){
    		var cols = [];
    		for (var i=0;i<this.colNames.length;i++){
    			if (this.colNames[i] !== this.name){
    				cols.push(this.colNames[i]);
    			}
    		}
    		return cols;
    	}
    },
    components: {
    	taskCard: TaskCard
    }
    
}
</script>