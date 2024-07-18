<script>
  import {mapGetters} from 'vuex'

  export default {
    name: "Common",

    computed: {
      ...mapGetters([
        'settings',
        'authUser',
        'csrf'
      ])
    },
  }
</script>
