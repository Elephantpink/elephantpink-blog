<template>
  <div class="blog-page">
    <nav class="topbar-container">
      <div class="middle-container">
        <transition name="expand-left">
          <div class="search-container" v-show="showSearchBox">
            <input
              type="text"
              id="search-box"
              class="search-box"
              ref="searchbox"
              name="search"
              v-model="postsTableConfig.filterBy"
              @blur="blurEvent($event)"
            />
          </div>
        </transition>
        <button id="btn-search" type="button" class="btn-search" @click="toggleSearchbox()">
          <svg-vue icon="magnifying-glass" />
          {{ $root.trans('blog_admin.posts.search') }}
        </button>
      </div>
      <div class="right-container">
        <router-link :to="{ name: 'admin-post-create' }">
          <svg-vue icon="plus-round" />
          {{ $root.trans('blog_admin.posts.create_new') }}
        </router-link>
      </div>
    </nav>

    <div class="view-wrapper">
      <div class="view-container">
        <div class="table-container">
          <orderable-headers-component
            :config="postsTableConfig"
            :extraHeader="true"
            @update-order-by="updateOrderBy($event)"
          />
          <div class="posts-list">
            <div v-for="post in postsList" :key="'post-' + post.id">
              <post-list-item-component :post="post" @delete-post="confirmDelete($event)" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <transition name="fade">
      <div class="overlay" v-show="showDeletePost" @click="showDeletePost = false">
        <div @click.stop.prevent>
          <span class="overlay-title">
            <svg-vue icon="risk" class="icon" />
            {{ postToDeleteTitle }}
          </span>
          <div class="overlay-content">
            <span>{{ $root.trans('blog_admin.posts.confirm_delete') }}</span>
            {{ $root.trans('blog_admin.posts.not_undone') }}
          </div>
          <div class="buttons-container">
            <button type="button" class="btn-cancel" @click="closeDeleteModal()">
              {{ $root.trans('blog_admin.posts.cancel') }}
            </button>
            <button type="button" class="cta" @click="deletePost()">
              {{ $root.trans('blog_admin.posts.delete') }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
// Do your imports here
import { mapGetters } from "vuex";
import OrderableHeadersComponent from "../components/OrderableHeadersComponent";
import PostListItemComponent from "../components/PostListItemComponent";

export default {
  components: {
    OrderableHeadersComponent,
    PostListItemComponent
  },
  data() {
    return {
      editing: false,
      errors: null,
      postsTableConfig: {
        headers: ["blog_admin.posts.title", "blog_admin.posts.image", "blog_admin.posts.date", "blog_admin.posts.author"],
        fields: ["titleField", "thumbnail_url", "date", "author"],
        filterBy: "",
        orderBy: [3]
      },
      showSearchBox: false,
      showDeletePost: false,
      postToDelete: null
    };
  },
  computed: {
    ...mapGetters(["posts"]),
    postsList: function() {
      let postList = JSON.parse(JSON.stringify(this.posts));

      Object.values(postList).forEach(p => {
        p.author = this.$store.getters.getAuthorById(p.author_id).name;
        p.titleField = {
          text: p.title,
          route: { name: "admin-post-edit", params: { slug: p.slug } }
        };
        let date = new Date(p.created_at);
        p.date =
          date.getDate() +
          "/" +
          (date.getMonth() + 1) +
          "/" +
          date.getFullYear();

        if (p.publish_date) {
          p.created_at = p.publish_date;
        }
        p.created_at = new Date(p.created_at);
      });

      return this.$root.prepareTable(
        Object.values(postList),
        this.postsTableConfig.fields,
        this.postsTableConfig.orderBy,
        { title: this.postsTableConfig.filterBy }
      );
    },
    postToDeleteTitle: function() {
      return this.postToDelete ? this.postToDelete.title : "";
    }
  },
  methods: {
    blurEvent(event) {
      if (!event.relatedTarget || event.relatedTarget.id != "btn-search") {
        this.showSearchBox = false;
      }
    },
    closeDeleteModal() {
      this.showDeletePost = false;
      this.postToDelete = null;
    },
    confirmDelete(event) {
      this.showDeletePost = true;
      this.postToDelete = event;
    },
    deletePost() {
      this.$store.dispatch("deletePost", this.postToDelete);
      this.showDeletePost = false;
    },
    toggleSearchbox() {
      this.showSearchBox = true;
      this.$nextTick(() => {
        this.$refs.searchbox.focus();
      });
    },
    updateOrderBy(field) {
      if (Math.abs(this.postsTableConfig.orderBy[0]) === field) {
        Vue.set(
          this.postsTableConfig.orderBy,
          0,
          this.postsTableConfig.orderBy[0] * -1
        );
      } else {
        Vue.set(this.postsTableConfig.orderBy, 0, field);
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.posts-list {
  display: flex;
  flex-direction: column;
}

.overlay {
  .overlay-content {
    span {
      font-family: "rubikmedium";
      margin-bottom: 10px;
      display: block;
    }
  }
}

@media only screen and (max-width: 990px) {
  .expand-left-enter-active {
    animation: expand-left 0.3s linear;
  }

  .expand-left-leave-active {
    animation: expand-left 0.3s linear reverse;
  }

  @keyframes expand-left {
    from {
      height: 0;
      bottom: 0;
    }
    to {
      height: 55px;
      bottom: calc(-100% + 1px);
    }
  }
}
</style>