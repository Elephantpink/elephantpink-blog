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
              v-model="authorsTableConfig.filterBy"
              @blur="blurEvent($event)"
            />
          </div>
        </transition>
        <button id="btn-search" type="button" class="btn-search" @click="toggleSearchbox()">
          <svg-vue icon="magnifying-glass" />
          {{ $root.trans('blog_admin.authors.search') }}
        </button>
      </div>
      <div class="right-container">
        <button type="button" @click="showAuthorModal = true">
          <svg-vue icon="plus-round" />
          {{ $root.trans('blog_admin.authors.create_new') }}
        </button>
      </div>
    </nav>

    <div class="view-wrapper">
      <div class="view-container">
        <div class="table-container">
          <orderable-headers-component
            :config="authorsTableConfig"
            :extraHeader="true"
            @update-order-by="updateOrderBy($event)"
          />
          <div class="authors-list">
            <author-list-item-component
              v-for="(author, index) in authorsList"
              :key="'author-' + index"
              :author="author"
              @edit-author="displayAuthorModal($event)"
              @toggle-disable-author="displayConfirmDisableAuthor($event)"
            />
          </div>
        </div>
      </div>
    </div>

    <transition name="fade">
      <div class="overlay" v-show="showAuthorModal" @click="closeAuthorModal()">
        <div class="form-wrapper" @click.stop>
          <button type="button" class="btn-close" @click="closeAuthorModal()">
            {{ $root.trans('blog_admin.authors.close') }} X
          </button>
          <form @submit.prevent="submitAuthor()">
            <div class="form-container">
              <div class="field-container">
                <label for="name">
                  {{ $root.trans('blog_admin.authors.name') }}
                </label>
                <input type="text" id="name" name="name" v-model="formAuthor.name" required />
                <div class="errors">
                  <span
                    v-for="(error, index) in authorFormErrors.name"
                    :key="'error-slug-' + index"
                  >{{ error }}</span>
                </div>
              </div>
              <div class="field-container">
                <label for="email">
                  {{ $root.trans('blog_admin.authors.email') }}
                </label>
                <input type="email" id="email" name="email" v-model="formAuthor.email" required />
                <div class="errors">
                  <span
                    v-for="(error, index) in authorFormErrors.email"
                    :key="'error-slug-' + index"
                  >{{ error }}</span>
                </div>
              </div>
              <div class="field-container">
                <label for="additional_information">
                  {{ $root.trans('blog_admin.authors.additional_information') }}
                </label>
                <input
                  type="text"
                  id="additional_information"
                  name="additional_information"
                  v-model="formAuthor.additional_information"
                />
              </div>
              <div class="field-container">
                <label for="password">
                  {{ $root.trans('blog_admin.authors.password') }}
                </label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  v-model="formAuthor.password"
                  :required="!formAuthor.id"
                />
                <div class="errors">
                  <span
                    v-for="(error, index) in authorFormErrors.password"
                    :key="'error-slug-' + index"
                  >{{ error }}</span>
                </div>
              </div>
              <div class="field-container">
                <label for="password-confirm">
                  {{ $root.trans('blog_admin.authors.confirm_password') }}
                </label>
                <input
                  type="password"
                  id="password-confirm"
                  name="password-confirm"
                  v-model="formAuthor.password_confirm"
                  :required="!formAuthor.id"
                />
                <span
                  class="password-confirm-error"
                  v-show="!passwordConfirmValid"
                >
                  {{ $root.trans('blog_admin.authors.password_not_match') }}
                </span>
              </div>
              <div class="field-container">
                <div class="checkbox-container">
                  <label>
                    <input type="checkbox" name="autogenerate" v-model="formAuthor.is_admin" />
                    <span class="check" :class="{ 'active': formAuthor.is_admin }" />
                    {{ $root.trans('blog_admin.authors.admin_privileges') }}
                  </label>
                </div>
              </div>
            </div>
            <div class="buttons-container">
              <button
                type="submit"
                class="cta full"
                :class="{ 'btn-cancel': !passwordConfirmValid }"
                :disabled="!passwordConfirmValid"
              >{{ authorModalSubmitText }}</button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div class="overlay" v-show="showDisableModal" @click="closeDisableModal()">
        <div @click.stop>
          <span class="overlay-title">
            <svg-vue icon="risk" class="icon" />
            {{ authorToDisableTitle }}
          </span>
          <div class="overlay-content">
            <div>{{ disableModalConfirmation }}</div>
            {{ disableModalText }}
          </div>
          <div class="buttons-container">
            <button type="button" class="btn-cancel" @click="closeDisableModal()">
              {{ $root.trans('blog_admin.authors.cancel') }}
            </button>
            <button
              type="button"
              class="cta"
              @click="toggleDisableAuthor()"
            >{{ disableModalButtonText }}</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
// Do your imports here
import { mapGetters } from "vuex";
import AuthorListItemComponent from "../components/AuthorListItemComponent";
import OrderableHeadersComponent from "../components/OrderableHeadersComponent";

export default {
  components: {
    // Add your imported components here.
    AuthorListItemComponent,
    OrderableHeadersComponent
  },
  data() {
    return {
      // Add here the variables for this component.
      formAuthor: {
        name: "",
        email: "",
        password: "",
        password_confirm: "",
        additional_information: "",
        is_admin: false
      },
      authorFormErrors: [],
      authorsTableConfig: {
        fields: [
          "name",
          "email",
          "posts",
          "additional_information",
          "last_login"
        ],
        headers: [
          "blog_admin.authors.name",
          "blog_admin.authors.email",
          "blog_admin.authors.posts",
          "blog_admin.authors.additional_information",
          "blog_admin.authors.last_login"
        ],
        filterBy: "",
        orderBy: [-1]
      },
      showSearchBox: false,
      showAuthorModal: false,
      showDisableModal: false,
      authorToDisable: null
    };
  },
  computed: {
    // Define your computed variables (reactive) here.
    ...mapGetters(["authors", "posts"]),
    authorModalSubmitText: function() {
      let text = this.formAuthor.id ? "save_changes" : "create";

      return this.$root.trans('blog_admin.authors.' + text)
    },
    authorToDisableTitle: function() {
      return this.authorToDisable
        ? this.authorToDisable.name + " (" + this.authorToDisable.email + ")"
        : "";
    },
    authorsList: function() {
      let authorsList = JSON.parse(JSON.stringify(this.authors));

      Object.values(authorsList).forEach(author => {
        author.posts = this.posts.filter(post => {
          return post.author_id === author.id;
        }).length;
      });

      return this.$root.prepareTable(
        Object.values(authorsList),
        this.authorsTableConfig.fields,
        this.authorsTableConfig.orderBy,
        { name: this.authorsTableConfig.filterBy }
      );
    },
    disableModalConfirmation: function() {
      let text = "";

      if (this.authorToDisable) {
        text = this.authorToDisable.is_disabled ? "confirm_enable" : "confirm_disable";
      }

      return this.$root.trans('blog_admin.authors.' + text);
    },
    disableModalText: function() {
      let text = "";

      if (this.authorToDisable) {
        text = this.authorToDisable.is_disabled
          ? "enable_description"
          : "disable_description";
      }

      return this.$root.trans('blog_admin.authors.' + text);
    },
    disableModalButtonText: function() {
      let text = "";

      if (this.authorToDisable) {
        text = this.authorToDisable.is_disabled ? "enable" : "disable";
      }

      return this.$root.trans('blog_admin.authors.' + text);
    },
    formErrors: function() {
      return this.errors;
    },
    passwordConfirmValid: function() {
      return this.formAuthor.password === this.formAuthor.password_confirm;
    }
  },
  methods: {
    // Define your component methods here.
    blurEvent(event) {
      if (!event.relatedTarget || event.relatedTarget.id != "btn-search") {
        this.showSearchBox = false;
      }
    },
    createAuthor() {
      this.errors = null;
      this.$store
        .dispatch("createAuthor", this.formAuthor)
        .then(response => {
          this.closeAuthorModal();
        })
        .catch(error => {
          this.displayErrors(error);
        });
    },
    closeAuthorModal() {
      this.showAuthorModal = false;
      setTimeout(() => {
        this.formAuthor = {
          name: "",
          email: "",
          password: "",
          password_confirm: "",
          additional_information: "",
          is_admin: false
        };
      }, 300);
    },
    closeDisableModal() {
      this.showDisableModal = false;
      this.authorToDisable = null;
    },
    displayAuthorModal(event) {
      this.formAuthor = event;
      this.showAuthorModal = true;
    },
    displayConfirmDisableAuthor(event) {
      this.authorToDisable = event;
      this.showDisableModal = true;
    },
    displayErrors(error, errorType) {
      let errorObject = Object.assign({}, error);
      errorObject = errorObject.response.data.errors;
      Object.keys(errorObject).forEach(key => {
        Vue.set(this.authorFormErrors, key, errorObject[key]);
      });
    },
    editAuthor() {
      this.errors = null;
      this.$store
        .dispatch("editAuthor", this.formAuthor)
        .then(response => {
          this.closeAuthorModal();
        })
        .catch(error => {
          this.displayErrors(error);
        });
    },
    submitAuthor() {
      if (this.formAuthor.id) {
        this.editAuthor();
      } else {
        this.createAuthor();
      }
    },
    toggleDisableAuthor() {
      let author = Object.assign({}, this.authorToDisable);
      author.is_disabled = !author.is_disabled;
      this.$store
        .dispatch("editAuthor", author)
        .then(response => {
          this.closeDisableModal();
        })
        .catch(error => {
          this.displayErrors(error);
        });
    },
    toggleSearchbox() {
      this.showSearchBox = true;
      this.$nextTick(() => {
        this.$refs.searchbox.focus();
      });
    },
    updateOrderBy(field) {
      if (Math.abs(this.authorsTableConfig.orderBy[0]) === field) {
        Vue.set(
          this.authorsTableConfig.orderBy,
          0,
          this.authorsTableConfig.orderBy[0] * -1
        );
      } else {
        Vue.set(this.authorsTableConfig.orderBy, 0, field);
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.checkbox-container {
  position: relative;
  display: flex;
  align-items: center;

  label {
    margin: 20px 0 0;
    align-items: center;
    display: flex;
    cursor: pointer;
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
    margin: 0 10px 0 0;
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
</style>