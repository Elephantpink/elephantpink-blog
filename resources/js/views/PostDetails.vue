<template>
  <div class="blog-page">
    <form @submit.prevent="savePost()" class="blog-page">
      <nav class="topbar-container">
        <div class="left-container">
          <router-link :to="{ name: 'admin-posts' }">
            <svg-vue class="icon" icon="arrow" />
            {{ $root.trans('blog_admin.posts.back_to_posts') }}
          </router-link>
        </div>
        <div class="right-container">
          <button type="submit">
            {{ $root.trans('blog_admin.posts.save_changes') }}
          </button>
        </div>
      </nav>

      <div ref="view" class="view-wrapper">
        <div class="view-container">
          <div class="post-panel">
            <div class="post-details-container">
              <div class="post-details">
                <div class="post-basic">
                  <div class="field-container title-container">
                    <label for="post-title">{{ $root.trans('blog_admin.posts.title') }}</label>
                    <input
                      type="text"
                      id="post-title"
                      name="post-title"
                      v-model="formPost.title"
                      placeholder="Post title"
                      required
                    />
                  </div>
                  <div class="field-container">
                    <label for="post-subtitle">
                      {{ $root.trans('blog_admin.posts.subtitle') }}
                    </label>
                    <input
                      type="text"
                      id="post-subtitle"
                      name="post-subtitle"
                      v-model="formPost.subtitle"
                      placeholder="Post subtitle"
                    />
                  </div>
                  <div class="field-container schedule-container">
                    <button type="button" @click="showProgramOverlay = true">
                      <svg-vue icon="clock" class="icon" />
                      <span v-if="formPost.publish_date">{{ publishDate }}</span>
                      <span v-if="formPost.publish_date">|</span>
                      <span v-if="formPost.publish_date">
                        {{ $root.trans('blog_admin.posts.edit') }}
                      </span>
                      <span v-if="!formPost.publish_date">
                        {{ $root.trans('blog_admin.posts.program_for_later') }}
                      </span>
                    </button>
                  </div>
                </div>

                <div class="tags-categories-container">
                  <div class="field-container">
                    <div v-for="(t, index) in maxTags" :key="'tag-' + index" class="field">
                      <button
                        type="button"
                        @click="showTagsSelector(index)"
                        :class="{ 'active': showTags[index], 'selected': formPost.tags[index] }"
                      >
                        <span v-if="!formPost.tags[index]">
                          {{ $root.trans('blog_admin.posts.add_tag') }} +
                        </span>
                        <span v-else>
                          {{ getTagName(formPost.tags[index]) }}
                          <button
                            type="button"
                            class="remove"
                            @click.stop="removeTag(formPost.tags[index])"
                          >
                            <svg-vue icon="round-close" />
                          </button>
                        </span>
                      </button>
                      <transition name="fade">
                        <div class="selector" v-show="showTags[index]">
                          <input type="text" v-model="filterTags" placeholder="Search" />
                          <button
                            type="button"
                            v-for="tag in tagsList"
                            :key="'tag-' + tag.id"
                            @click="addTagToPost(tag.id, index)"
                          >
                            <span>{{ tag.name }}</span>
                            <span>+</span>
                          </button>
                        </div>
                      </transition>
                    </div>
                  </div>

                  <div class="field-container">
                    <div
                      v-for="(c, index) in maxCategories"
                      :key="'category-' + index"
                      class="field"
                    >
                      <button
                        type="button"
                        @click="showCategoriesDropdowns(index)"
                        :class="{ 'active': showCategories[index], 'selected': formPost.categories[index] }"
                      >
                        <span v-if="!formPost.categories[index]">
                          {{ $root.trans('blog_admin.posts.add_category') }} +
                        </span>
                        <span v-else>
                          {{ getCategoryName(formPost.categories[index]) }}
                          <button
                            type="button"
                            class="remove"
                            @click.stop="removeCategory(formPost.categories[index])"
                          >
                            <svg-vue icon="round-close" />
                          </button>
                        </span>
                      </button>
                      <transition name="fade">
                        <div class="selector" v-show="showCategories[index]">
                          <input type="text" v-model="filterCategories" placeholder="Search" />
                          <button
                            type="button"
                            v-for="category in categoriesList"
                            :key="'category-' + category.id"
                            @click="addCategoryToPost(category.id, index)"
                          >
                            <span>{{ category.name }}</span>
                            <span>+</span>
                          </button>
                        </div>
                      </transition>
                    </div>
                  </div>

                  <div
                    class="transparent-overlay"
                    @click="closeSelectors()"
                    v-show="showDropdownOverlay"
                  ></div>
                </div>
              </div>

              <div class="post-thumbnail" @click="$refs.thumbnail.click()">
                {{ $root.trans('blog_admin.posts.add_image') }} +
                <input
                  type="file"
                  id="thumbnail"
                  ref="thumbnail"
                  @change="previewImage($event)"
                />
                <img :src="displayedThumbnail" />
                <button type="button" class="crop-btn" @click.stop="recropHeader()">
                  <svg-vue icon="crop" />
                  {{ $root.trans('blog_admin.posts.crop_thumbnail') }}
                </button>
                <canvas ref="canvas" v-show="false" />
                <img ref="imgTest" :src="formPost.header_image_url" v-show="false" />
              </div>
            </div>

            <div class="field-container slug-container">
              <label for="slug">
                {{ $root.trans('blog_admin.posts.slug') }}
              </label>
              <div class="checkbox-container">
                <input
                  type="text"
                  v-model="formPost.slug"
                  placeholder="your-post-slug"
                  pattern="[a-z0-9-]+"
                  required
                />
                <label>
                  <input type="checkbox" name="autogenerate" v-model="autoslug" />
                  <span class="check" :class="{ 'active': autoslug }" />
                  {{ $root.trans('blog_admin.posts.autogenerate_from_title') }}
                </label>
              </div>
              <div class="errors">
                <span
                  v-for="(error, index) in errors.post.slug"
                  :key="'error-slug-' + index"
                >{{ error }}</span>
              </div>
            </div>

            <div class="field-container excerpt-container">
              <label for="post-excerpt">
                {{ $root.trans('blog_admin.posts.excerpt') }}
              </label>
              <div class="checkbox-container">
                <input
                  type="text"
                  v-model="formPost.excerpt"
                  placeholder="Post excerpt"
                  maxlength="128"
                />
                <label>
                  <input type="checkbox" name="autogenerate" v-model="autoexcerpt" />
                  <span class="check" :class="{ 'active': autoexcerpt }" />
                  {{ $root.trans('blog_admin.posts.autogenerate_from_content') }}
                </label>
              </div>
            </div>

            <div class="field-container body-container">
              <label for="post-body">
                {{ $root.trans('blog_admin.posts.content') }}
              </label>
              <quill-editor
                class="post-body"
                :content="formPost.body"
                @change="onEditorChange($event, 'body')"
              />
            </div>
          </div>
        </div>
      </div>
    </form>

    <transition name="fade">
      <div v-show="showCropper" class="overlay">
        <div class="overlay-container">
          <div class="top-buttons">
            <button type="button" class="btn-change" @click="reuploadImage()">
              <svg-vue icon="photo" class="icon" />
              {{ $root.trans('blog_admin.posts.change_image') }}
            </button>
            <button type="button" class="btn-reset" @click="reset()">
              <svg-vue icon="reload" class="icon" />
              {{ $root.trans('blog_admin.posts.reset') }}
            </button>
          </div>
          <vue-cropper ref="cropper" :aspect-ratio="1" :src="preview" />
          <div class="buttons-container">
            <button type="button" class="btn-cancel" @click="cancel()">
              {{ $root.trans('blog_admin.posts.cancel') }}
            </button>
            <button type="button" class="btn-cta" @click="crop()">
              {{ $root.trans('blog_admin.posts.crop_image') }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div v-show="showProgramOverlay" class="overlay" @click="showProgramOverlay = false">
        <div class="overlay-container" @click.stop>
          <button type="button" class="btn-close" @click="showProgramOverlay = false">
            {{ $root.trans('blog_admin.posts.close') }} X
          </button>
          <form @submit.prevent="programPost()">
            <div class="overlay-content">
              <div class="fields-wrapper date-container">
                <div class="field-container date">
                  <label for="date">
                    {{ $root.trans('blog_admin.posts.program_date') }}
                  </label>
                  <div>
                    <input
                      type="date"
                      ref="program_date"
                      name="program_date"
                      @change="updateDate($event)"
                      required
                    />
                  </div>
                </div>
                <div class="field-container time">
                  <label for="time">
                    {{ $root.trans('blog_admin.posts.program_hour') }}
                  </label>
                  <div>
                    <input
                      type="number"
                      ref="program_hours"
                      name="program_hours"
                      min="0"
                      max="23"
                      @change="updateHours($event)"
                      placeholder="00"
                      required
                    />
                    <input
                      type="number"
                      ref="program_minutes"
                      name="program_minutes"
                      min="0"
                      max="59"
                      @change="updateMinutes($event)"
                      placeholder="00"
                      required
                    />
                  </div>
                </div>
              </div>
              <div class="publish-date">
                {{ $root.trans('blog_admin.posts.publication_date') }} :
                <span>{{ publishDate }}</span>
              </div>
            </div>
            <div class="buttons-container">
              <button type="submit" class="btn-cta">
                {{ $root.trans('blog_admin.posts.program_post') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";

import { quillEditor } from "vue-quill-editor";
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";

import { mapGetters } from "vuex";

export default {
  components: {
    quillEditor,
    VueCropper
  },
  data() {
    return {
      formPost: {
        title: "",
        subtitle: "",
        excerpt: "",
        slug: "",
        body: "",
        categories: [],
        tags: [],
        publish_date: null,
        thumbnail_url: null,
        header_image: null
      },
      showTags: [],
      showCategories: [],
      filterTags: "",
      filterCategories: "",
      showCropper: false,
      preview: null,
      previousThumbnail: null,
      headerImage: null,
      cropImage: null,
      showProgramOverlay: false,
      program_post: {
        date: null,
        hours: null,
        minutes: null
      },
      autoslug: true,
      autoexcerpt: true,
      headerImageData: null,
      errors: {
        post: {}
      },
      maxTags: 3,
      maxCategories: 3,
      showDropdownOverlay: false
    };
  },
  computed: {
    ...mapGetters(["tags", "categories", "currentUser"]),
    isNewPost: function() {
      return this.$route.name === "admin-post-create";
    },
    ctaText: function() {
      return this.isNewPost ? "Create" : "Save changes";
    },
    storePost: function() {
      return !this.isNewPost
        ? this.$store.getters.getPostBySlug(this.$route.params.slug)
        : null;
    },
    categoriesList: function() {
      return this.categories.filter(category => {
        return (
          category.name
            .toLowerCase()
            .indexOf(this.filterCategories.toLowerCase()) !== -1 &&
          this.formPost.categories.indexOf(category.id) === -1
        );
      });
    },
    tagsList: function() {
      return this.tags.filter(tag => {
        return (
          tag.name.toLowerCase().indexOf(this.filterTags.toLowerCase()) !==
            -1 && this.formPost.tags.indexOf(tag.id) === -1
        );
      });
    },
    publishDate: function() {
      let publish_date = this.formPost.publish_date
        ? new Date(this.formPost.publish_date)
        : null;
      let final = this.$root.trans('blog_admin.posts.now');

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
    },
    postTitle: function() {
      return this.formPost ? this.formPost.title : "";
    },
    displayedThumbnail: function() {
      return this.cropImage ? this.cropImage : this.previousThumbnail;
    }
  },
  methods: {
    displayErrors(error, errorType) {
      let errorObject = Object.assign({}, error);
      errorObject = errorObject.response.data.errors;
      Object.keys(errorObject).forEach(key => {
        Vue.set(this.errors[errorType], key, errorObject[key]);
      });
    },
    recropHeader() {
      if (this.headerImageData) {
        this.preview = this.headerImageData;
        this.$refs.cropper.replace(this.preview);
      } else {
        let context = this.$refs.canvas.getContext("2d");
        this.$refs.canvas.width = this.$refs.imgTest.width;
        this.$refs.canvas.height = this.$refs.imgTest.height;
        context.drawImage(this.$refs.imgTest, 0, 0);
        let originalHeaderData = this.$refs.canvas.toDataURL();
        this.preview = originalHeaderData;
        this.$refs.cropper.replace(originalHeaderData);
      }
      this.showCropper = true;
    },
    addCategoryToPost(id, index) {
      if (this.formPost.categories[index]) {
        this.formPost.categories[index] = id;
      } else {
        this.formPost.categories.push(id);
      }
      this.closeSelectors();
    },
    addTagToPost(id, index) {
      if (this.formPost.tags[index]) {
        this.formPost.tags[index] = id;
      } else {
        this.formPost.tags.push(id);
      }
      this.closeSelectors();
    },
    cancel() {
      this.$refs.thumbnail.value = null;
      this.showCropper = false;
      this.preview = null;
    },
    closeSelectors() {
      // this.showCategories = false
      // this.showTags = false
      // this.resetShowTags()
      this.resetShowDropdowns();
    },
    crop() {
      this.$refs.thumbnail.value = null;
      this.cropImage = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.showCropper = false;
      this.headerImageData = this.preview;
      this.preview = null;
    },
    getCategoryName(id) {
      return this.categories.length
        ? this.$store.getters.getCategoryById(id).name
        : "Loading...";
    },
    getTagName(id) {
      return this.tags.length
        ? this.$store.getters.getTagById(id).name
        : "Loading...";
    },
    onEditorChange(event, field) {
      if (this.autoexcerpt) {
        let maxIndex = event.text.indexOf("\n");
        if (maxIndex > 128) maxIndex = 128;
        this.formPost.excerpt = event.text.substr(0, maxIndex);
      }
      this.formPost[field] = event.html;
    },
    previewImage(event) {
      let reader = new FileReader();

      reader.onload = e => {
        this.preview = e.target.result;
        this.$refs.cropper.replace(e.target.result);
        this.showCropper = true;
      };

      reader.readAsDataURL(event.target.files[0]);
      this.headerImage = event.target.files[0];
    },
    programPost() {
      if (
        this.program_post.date &&
        this.program_post.hours &&
        this.program_post.minutes
      ) {
        this.formPost.publish_date =
          this.program_post.date +
          " " +
          this.program_post.hours +
          ":" +
          this.program_post.minutes;

        this.program_post.date = null;
        this.program_post.hours = null;
        this.program_post.minutes = null;

        this.$refs.program_date.value = null;
        this.$refs.program_hours.value = null;
        this.$refs.program_minutes.value = null;

        this.showProgramOverlay = false;
      }
    },
    removeCategory(category) {
      this.formPost.categories.splice(
        this.formPost.categories.indexOf(category),
        1
      );
    },
    removeTag(tag) {
      this.formPost.tags.splice(this.formPost.tags.indexOf(tag), 1);
    },
    reset() {
      this.$refs.cropper.reset();
    },
    reuploadImage() {
      this.cancel();
      this.$refs.thumbnail.click();
    },
    savePost() {
      if (this.isNewPost) {
        this.formPost.author_id = this.currentUser.id
        this.$store
          .dispatch("publishPost", {
            post: this.formPost,
            categories: this.formPost.categories,
            tags: this.formPost.tags,
            thumbnail: this.cropImage,
            header: this.headerImage
          })
          .then(response => {
            this.formPost = {
              title: "",
              subtitle: "",
              excerpt: "",
              slug: "",
              body: "",
              categories: [],
              tags: [],
              publish_date: null,
              thumbnail_url: null,
              header_image: null
            };
            this.$router.push({ name: "admin-posts" });
          })
          .catch(error => {
            this.displayErrors(error, "post");
          });
      } else {
        this.$store
          .dispatch("editPost", {
            post: this.formPost,
            categories: this.formPost.categories,
            tags: this.formPost.tags,
            thumbnail: this.cropImage,
            header: this.headerImage
          })
          .then(response => {
            this.formPost = {
              title: "",
              subtitle: "",
              excerpt: "",
              slug: "",
              body: "",
              categories: [],
              tags: [],
              publish_date: null,
              thumbnail_url: null,
              header_image: null
            };
            this.$router.push({ name: "admin-posts" });
          })
          .catch(error => {
            this.displayErrors(error, "post");
          });
      }
    },
    updateDate(event) {
      this.program_post.date = event.target.value;
    },
    updateHours(event) {
      // this.program_post.hours = event.target.value < 10 ? '0' + event.target.value : event.target.value
      this.program_post.hours = event.target.value;
      if (this.program_post.hours < 10 && this.program_post.minutes.length < 2) {
        this.program_post.hours = "0" + this.program_post.hours;
      }
    },
    updateMinutes(event) {
      // this.program_post.minutes = event.target.value < 10 ? '0' + event.target.value : event.target.value
      this.program_post.minutes = event.target.value;
      if (this.program_post.minutes < 10 && this.program_post.minutes.length < 2) {
        this.program_post.minutes = "0" + this.program_post.minutes;
      }
    },
    updateSlug(event) {
      if (this.autoslug) {
        this.formPost.slug = event.target.value;
      }
    },
    showCategoriesDropdowns(index) {
      this.resetShowDropdowns();
      Vue.set(this.showCategories, index, true);
      this.showDropdownOverlay = true;
    },
    showTagsSelector(index) {
      this.resetShowDropdowns();
      Vue.set(this.showTags, index, true);
      this.showDropdownOverlay = true;
    },
    resetShowDropdowns() {
      for (let i = 0; i < this.maxTags; i++) {
        this.showTags[i] = false;
      }
      for (let i = 0; i < this.maxCategories; i++) {
        this.showCategories[i] = false;
      }
      this.showDropdownOverlay = false;
    }
  },
  watch: {
    $route: function() {
      this.cropImage = null;
      this.previousThumbnail = null;
      this.headerImageData = null;
    },
    storePost: function(updated) {
      if (updated) {
        this.formPost = JSON.parse(JSON.stringify(updated));
      } else {
        this.formPost = {
          title: "",
          subtitle: "",
          excerpt: "",
          slug: "",
          body: "",
          categories: [],
          tags: [],
          publish_date: null,
          thumbnail_url: null,
          header_image: null
        };
      }
      this.$nextTick(() => {
        this.$refs.view.scrollTop = 0
      })
    },
    formPost: function(post) {
      if (post && post.thumbnail_url) {
        this.previousThumbnail = post.thumbnail_url;
      }
    },
    postTitle: function(updated) {
      if (this.autoslug && this.formPost) {
        this.formPost.slug = updated
          .toLowerCase()
          .replace(/ /g, "-")
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")
          .replace(/[^a-z0-9 -]/g, "");
      }
    }
  },
  created() {
    this.resetShowDropdowns();
  },
  mounted() {
    if (!this.isNewPost) {
      let post = this.$store.getters.getPostBySlug(this.$route.params.slug);
      if (post) {
        this.formPost = JSON.parse(JSON.stringify(post));
      }
    }
    this.$nextTick(() => {
      this.$refs.view.scrollTop = 0
    })
  }
};
</script>

<style lang="scss" scoped>
// .post-view,
// form {
//   height: 100%;
// }
.blog-page {

.topbar-container {
  .left-container {
    a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;

      .icon {
        width: 15px;
        margin-left: 0;
      }
    }
  }
}

.post-panel {
  background-color: #ffffff;
  padding: 50px 30px;
  max-width: 1920px;
  width: 100%;
  box-sizing: border-box;

  .post-details-container {
    display: flex;
    position: relative;

    .post-details {
      width: calc(100% - 240px);

      > div {
        display: flex;
      }

      .tags-categories-container {
        display: flex;
        margin-top: 10px;
        flex-direction: column;

        .field {
          position: relative;
        }

        .field-container {
          flex-direction: row;
          position: relative;
          // width: auto;
          // min-width: 25%;
          // max-width: 50%;
          // display: inline-block;
          display: flex;

          > div > button {
            display: inline-block;
            height: 50px;
            transition: all 0.15s linear;
            text-transform: capitalize;
            margin: 15px 20px 0;
            background-color: #f6f6f6;
            padding: 15px 30px;

            &.active {
              background-color: #0a1026;
              color: white;
            }

            &:first-child {
              margin-left: 0;
            }

            &:last-child {
              margin-right: 0;
            }
          }

          .selected {
            // padding: 0 15px;
            // margin: 15px 10px 0;
            // height: 50px;
            // display: inline-flex;
            align-items: center;
            background-color: #d8875c;
            color: white;
            position: relative;
            z-index: 1;

            .remove {
              display: block;
              position: absolute;
              width: 20px;
              height: 20px;
              right: 0px;
              top: 0px;
              transform: translate(0%, -50%);
              cursor: pointer;
              z-index: 2;
              padding: 0;
              background: transparent;
              border: 0;
            }
          }

          .selector {
            position: absolute;
            bottom: 0;
            left: 0;
            z-index: 3;
            background-color: #ffffff;
            transform: translateY(100%);
            padding: 10px;
            box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.15);

            input {
              margin-bottom: 10px;
            }

            button {
              height: 30px;
              width: 100%;
              justify-content: space-between;
              background: #ffffff;
            }
          }
        }

        .transparent-overlay {
          position: absolute;
          z-index: 1;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: transparent;
        }
      }
    }

    .post-thumbnail {
      width: 200px;
      margin-left: 40px;
      // height: 135px;
      height: 200px;
      box-sizing: border-box;
      position: relative;
      align-self: flex-start;
      background-color: #f6f6f6;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;

      input {
        padding: 0;
        width: 100%;
        display: none;
      }

      img {
        width: 100%;
        height: auto;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 2;
        cursor: pointer;
      }

      .crop-btn {
        position: absolute;
        bottom: -30px;
        left: 50%;
        width: 100%;
        transform: translateX(-50%);
        text-transform: capitalize;
        background: transparent;
      }
    }
  }

  .field-container {
    // display: flex;
    // flex-direction: column;
    // width: 100%;
    padding-right: 20px;
    // margin-bottom: 30px;

    &.schedule-container {
      align-items: center;
      justify-content: flex-end;

      button {
        height: 50px;
        color: white;
        text-transform: uppercase;
        background-color: #0a1026;
        width: 275px;
        justify-content: space-evenly;
        // padding: 0 20px;
        padding: 0;

        // > span {
        //   display: flex;
        //   width: 100%;
        //   justify-content: space-between;
        // }

        .icon {
          // margin-left: 0;
          margin: 0;
        }
      }
    }

    &.slug-container,
    &.excerpt-container {
      width: 50%;

      input[type="text"] {
        width: 40%;
      }

      .checkbox-container {
        position: relative;
        display: flex;
        align-items: center;

        label {
          margin: 0;
          align-items: center;
          display: flex;
          cursor: pointer;
          margin-left: 20px;
        }

        input[type="checkbox"] {
          height: 0;
          width: 0;
          position: absolute;
          opacity: 0;
        }

        .check {
          width: 16px;
          height: 16px;
          border: 1px solid #021527;
          border-radius: 100%;
          display: inline-flex;
          content: "";
          margin: 0 10px;
          background-color: white;
          position: relative;

          &.active:after {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8px;
            height: 8px;
            background-color: #021527;
            border-radius: 100%;
            display: block;
            content: "";
            transform: translate(-50%, -50%);
          }
        }
      }
    }

    &.excerpt-container {
      width: 100%;

      input[type="text"] {
        width: 60%;
      }
    }

    &.body-container {
      width: 100%;
      margin-bottom: 0;

      textarea {
        width: 100%;
      }
    }

    label {
      margin-bottom: 15px;
    }

    input,
    textarea {
      background-color: #f6f6f6;
      border: 0;
    }

    input {
      height: 50px;
      padding: 0 15px;
    }
  }
}

.overlay {
  .overlay-container {
    
    .top-buttons {
      display: flex;
      width: 100%;
    }

    .overlay-content {
      background: #ffffff;
      padding: 30px 40px;

      > div {
        display: flex;
        justify-content: space-evenly;
        margin-bottom: 30px;

        &.fields-wrapper {
          justify-content: center;

          > div {
            width: auto;

            &:first-child {
              margin-right: 20px;
            }

            input[type="number"]:first-child {
              margin-right: 15px;
            }
          }
        }

        &.publish-date {
          margin-bottom: 0;
          // justify-content: center;
          display: block;
          text-align: center;

          span {
            font-family: "rubikmedium";
            // margin-left: 1em;
          }
        }
      }
    }
  }

  .buttons-container {
    width: 100%;
    display: flex;
    margin-top: 20px;
    justify-content: center;
  }

  button {
    height: 55px;
    background-color: transparent;
    cursor: pointer;
    color: white;
    text-transform: uppercase;
    text-align: center;
    border: 1px transparent;
    width: 220px;
    margin: 0 10px;

    &.btn-reset,
    &.btn-change,
    &.btn-close {
      width: auto;
      margin: 0 0 10px;
      padding: 0;
      height: 30px;
      display: inline-flex;
    }

    &.btn-change {
      margin-right: 20px;

      .icon {
        fill: #ffffff;
      }
    }

    &.btn-cancel {
      border: 1px solid #ffffff;
    }

    &.btn-cta {
      background-color: #d8875c;
    }
  }
}

@media only screen and (max-width: 990px) {
  .post-panel {
    padding: 20px;

    .post-details-container {
      flex-direction: column;

      .post-details {
        width: 100%;

        > div {
          flex-direction: column;
        }

        .tags-categories-container {
          .field-container {
            flex-direction: column;

            .field {
              width: 100%;

              button {
                width: 100%;
              }

              .selector {
                width: 100%;
                box-sizing: border-box;

                > * {
                  height: 50px;
                }

                input {
                  width: 100%;
                  box-sizing: border-box;
                }
              }
            }
          }
        }
      }

      .post-thumbnail {
        width: 300px;
        height: 300px;
        margin: 30px auto;
      }
    }

    .field-container {
      padding: 0;

      &.schedule-container {
        margin: 30px 0 10px;

        button {
          width: 100%;
        }
      }

      &.slug-container,
      &.excerpt-container {
        width: 100%;

        .checkbox-container {
          flex-direction: column;

          input {
            width: 100%;
            box-sizing: border-box;
          }

          label {
            margin: 20px 0 0;
          } 
        } 
      }
    }
  }

  .overlay {
    .overlay-container {
      .overlay-content {
        .fields-wrapper.date-container {
          flex-direction: column;
          justify-content: left;
          text-align: left;

          > div:first-child {
            margin-right: 0;
          }
          
          .date,
          .time {
            display: block;

            label {
              margin: 0 0 10px;
              display: block;
            }
          }

          .date {
            input {
              width: 100%;
            }
          }

          .time {
            label {
              margin-top: 20px;
            }

            div {
              display: flex;
              justify-content: space-between;
            }
            
            input {
              width: calc(50% - 10px);
              box-sizing: border-box;
            }
          }
        }
      }
    }
  }
}
}

</style>