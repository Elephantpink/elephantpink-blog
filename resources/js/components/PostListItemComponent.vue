<template>
  <div>
    <div class="row">
      <div class="title">
        <router-link :to="post.titleField.route">{{ post.titleField.text }}</router-link>
      </div>
      <div class="thumbnail">
        <div
          class="post-thumbnail"
          v-if="post.thumbnail_url"
          :style="'background-image: url(\'' + post.thumbnail_url + '\')'"
        />
        <span v-else>{{ $root.trans('blog_admin.posts.none') }}</span>
      </div>
      <div class="date">{{ publishDate }}</div>
      <div class="author">{{ post.author }}</div>
      <div class="options">
        <button type="button" @click="showMenu = true">
          <svg-vue icon="menu" />
        </button>
        <transition name="fade">
          <div class="menu" v-show="showMenu">
            <router-link :to="post.titleField.route">
              <span>{{ $root.trans('blog_admin.posts.edit') }}</span>
              <svg-vue icon="edit" />
            </router-link>
            <button type="button" @click="deletePost()">
              <span>{{ $root.trans('blog_admin.posts.delete') }}</span>
              <svg-vue icon="close" />
            </button>
          </div>
        </transition>
      </div>
    </div>
    <div class="transparent-overlay" v-show="showMenu" @click="showMenu = false" />
  </div>
</template>

<script>
// Do your imports here

export default {
  components: {
    // Add your imported components here.
  },
  props: {
    post: { type: Object, default: null }
  },
  data() {
    return {
      showMenu: false
    };
  },
  computed: {
    // Define your computed variables (reactive) here.
    publishDate: function() {
      let publish_date = new Date(
        this.post.publish_date ? this.post.publish_date : this.post.created_at
      );
      let final = "";

      if (publish_date) {
        let date =
          (publish_date.getDate() < 10
            ? "0" + publish_date.getDate()
            : publish_date.getDate()) +
          "." +
          (publish_date.getMonth() < 9
            ? "0" + (publish_date.getMonth() + 1)
            : publish_date.getMonth() + 1) +
          "." +
          publish_date
            .getFullYear()
            .toString()
            .substr(-2, 2);
        let time =
          (publish_date.getHours() < 10
            ? "0" + publish_date.getHours()
            : publish_date.getHours()) +
          ":" +
          (publish_date.getMinutes() < 10
            ? "0" + publish_date.getMinutes()
            : publish_date.getMinutes());

        final = date + " @ " + time;
      }

      return final;
    }
  },
  methods: {
    // Define your component methods here.
    deletePost() {
      this.showMenu = false;
      this.$emit("delete-post", this.post);
    }
  },
  watch: {
    // Define your variable watchers here
  },
  created() {
    // Do stuff when the component is created
  },
  mounted() {
    // Do stuff every time the component is mounted
  }
};
</script>

<style lang="scss" scoped>
.post-thumbnail {
  max-width: 100px;
  background-size: cover;
  background-position: center;
  height: 50px;
}
</style>