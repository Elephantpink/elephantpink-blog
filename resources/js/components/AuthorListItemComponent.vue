<template>
  <div>
    <div class="row">
      <div class="title">
        {{ author.name }}
        <span class="responsive">({{ author.email}})</span>
      </div>
      <div>{{ author.email }}</div>
      <div>{{ author.posts }}</div>
      <div class="wrapped">{{ author.additional_information }}</div>
      <div>{{ lastLogin }}</div>
      <div class="options">
        <button type="button" @click="showMenu = true">
          <svg-vue icon="menu" />
        </button>
        <transition name="fade">
          <div class="menu" v-show="showMenu">
            <button type="button" @click="editAuthor()">
              <span>{{ $root.trans('blog_admin.authors.edit') }}</span>
              <svg-vue icon="edit" />
            </button>
            <button type="button" @click="toggleDisableAuthor()">
              <span>{{ toggleDisableButtonText }}</span>
              <svg-vue icon="close" v-show="!isDisabled" />
              <svg-vue icon="check" v-show="isDisabled" />
            </button>
          </div>
        </transition>
      </div>
    </div>
    <div class="transparent-overlay" v-show="showMenu" @click="showMenu = false" />
  </div>
</template>

<script>
export default {
  props: {
    author: { type: Object, default: null }
  },
  data() {
    return {
      showMenu: false
    };
  },
  computed: {
    lastLogin: function() {
      let final = "-";

      if (this.author.last_login) {
        let last_login = new Date(this.author.last_login);

        if (last_login) {
          let date =
            (last_login.getDate() < 10
              ? "0" + last_login.getDate()
              : last_login.getDate()) +
            "." +
            (last_login.getMonth() < 9
              ? "0" + (last_login.getMonth() + 1)
              : last_login.getMonth() + 1) +
            "." +
            last_login
              .getFullYear()
              .toString()
              .substr(-2, 2);
          let time =
            (last_login.getHours() < 10
              ? "0" + last_login.getHours()
              : last_login.getHours()) +
            ":" +
            (last_login.getMinutes() < 10
              ? "0" + last_login.getMinutes()
              : last_login.getMinutes());

          final = date + " @ " + time;
        }
      }

      return final;
    },
    toggleDisableButtonText: function() {
      let text = this.author.is_disabled ? "enable" : "disable";

      return this.$root.trans("blog_admin.authors." + text);
    },
    isDisabled () {
      return this.author.is_disabled
    }
  },
  methods: {
    editAuthor() {
      this.showMenu = false;
      this.$emit("edit-author", this.author);
    },
    toggleDisableAuthor() {
      this.showMenu = false;
      this.$emit("toggle-disable-author", this.author);
    }
  }
};
</script>

<style lang="scss" scoped>
.title {
  .responsive {
    display: none;
  }
}

@media only screen and (max-width: 990px) {
  .title {
    .responsive {
      display: inline-block;
      margin-left: 10px;
      color: #888888;
      font-family: rubiklight;
    }
  }
}
</style>