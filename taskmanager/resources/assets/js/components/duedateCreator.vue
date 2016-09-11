<template>
		<div>
			<date-picker :due-date.sync="dueDate"></date-picker>
		</div>
            
</template>

<script>
export default {
	props: ["dueDate"],
    data () {
        return {
        	addDueDate: false
        }
     }
}

</script>