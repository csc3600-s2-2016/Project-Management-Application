<template>
  <div class="root">
    {{ name }}
  </div>
</template>

<script lang="es6">
export default {
  data () {
    return {
      name: 'test single vue component file'
    };
  }
};
</script>

<style>
.profile {
    background: #eee;
    border: 1px solid #aaa;
    border-radius: 2em;
    margin: 2em auto;
    min-height: 150px;
    padding: 2em;
    width: 300px;
}
</style>
