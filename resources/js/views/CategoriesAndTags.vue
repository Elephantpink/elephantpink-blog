<template>
  <div class="blog-page">
    <nav class="topbar-container">
    </nav>

    <div class="view-wrapper">
      <div class="view-container">
        <div class="panel categories-panel">
          <form @submit.prevent="createCategory">
            <label for="new-category">
              {{ $root.trans('blog_admin.categories_and_tags.add_category') }}
            </label>
            <div class="input-wrapper">
              <input type="text" name="new-category" v-model="formCategory.name" required>
              <button type="submit" class="cta">
                {{ $root.trans('blog_admin.categories_and_tags.add') }} +
              </button>
            </div>
            <div class="errors">
              <span v-for="(error, index) in errors.category.name" :key="'category-error-' + index">
                {{ error }}
              </span>
            </div>
          </form>

          <div class="created-container">
            <transition-group name="fade" tag="div">
              <span 
                v-for="category in categories" 
                :key="'category-' + category.id" 
                class="select-box" 
                @click="toggleEdit(category, 'Category')"
              >
                {{ category.name }}
                <button type="button" class="btn-remove" @click.stop="deleteCategory(category)">
                  <svg-vue icon="round-close"/>
                </button>
              </span>
            </transition-group>
          </div>
        </div>
        <div class="panel tags-panel">
          <form @submit.prevent="createTag">
            <label for="new-tag">
              {{ $root.trans('blog_admin.categories_and_tags.add_tag') }}
            </label>
            <div class="input-wrapper">
              <input type="text" name="new-tag" v-model="formTag.name" required>
              <button type="submit" class="cta">
                {{ $root.trans('blog_admin.categories_and_tags.add') }} +
              </button>
            </div>
            <div class="errors">
              <span v-for="(error, index) in errors.tag.name" :key="'tag-error-' + index">
                {{ error }}
              </span>
            </div>
          </form>

          <div class="created-container">
            <transition-group name="fade" tag="div">
              <span 
                v-for="tag in tags" 
                :key="'tag-' + tag.id" 
                class="select-box" 
                @click="toggleEdit(tag, 'Tag')"
              >
                {{ tag.name }}
                <button type="button" class="btn-remove" @click.stop="deleteTag(tag)">
                  <svg-vue icon="round-close"/>
                </button>
              </span>
            </transition-group>
          </div>
        </div>
      </div>
    </div>

    <transition name="fade">
      <div class="overlay" @click="showEditingModal = false" v-show="showEditingModal">
        <div class="form-wrapper" @click.stop="">
          <button type="button" class="btn-close" @click="showEditingModal = false">
            {{ $root.trans('blog_admin.categories_and_tags.close') }} X
          </button>
          <form @submit.prevent="submitEdit()">
            <div class="form-container">
              <label for="edit-title">
                {{ editTitle }}
              </label>
              <input type="text" name="edit-title" v-model="editModel.name">
              <div class="errors">
                <span v-for="(error, index) in errors.edit.name" :key="'error-name-' + index">
                  {{ error }}
                </span>
              </div>
              <label for="edit-description">
                {{ editDescription }}
              </label>
              <input type="text" name="edit-description" v-model="editModel.description">
              <div class="errors">
                <span v-for="(error, index) in errors.edit.description" :key="'error-description-' + index">
                  {{ error }}
                </span>
              </div>
              <span class="last-modified">
                {{ $root.trans('blog_admin.categories_and_tags.last_modified')}}: 
                <span>{{ parseUpdatedAt }}</span>
              </span>
            </div>
            <div class="buttons-container">
              <button type="submit" class="cta full">
                {{ $root.trans('blog_admin.categories_and_tags.save_changes') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div class="overlay" v-show="showConfirmDeleteModal" @click="closeConfirmDeleteModal()">
        <div @click.stop>
          <span class="overlay-title">
            <svg-vue icon="risk" class="icon" />
            {{ confirmDeleteModalTitle }}
          </span>
          <div class="overlay-content">
            <div>{{ deleteTitle }}</div>
            {{ deleteDescription }}
          </div>
          <div class="buttons-container">
            <button type="button" class="btn-cancel" @click="closeConfirmDeleteModal()">
              {{ $root.trans('blog_admin.categories_and_tags.cancel') }}
            </button>
            <button type="button" class="cta" @click="confirmDelete()">
              {{ $root.trans('blog_admin.categories_and_tags.delete') }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>

  // Do your imports here
  import { mapGetters } from 'vuex'

  export default {
    data() {
      return {
        formTag: { name: '', description: '' },
        formCategory: { name: '', description: '' },
        formTag: { name: '', description: '' },
        editModel: { name: '', description: '', updated_at: '' },
        editingType: '',
        showEditingModal: false,
        showConfirmDeleteModal: false,
        errors: {
          category: [],
          tag: [],
          edit: []
        },
        toDelete: null,
        deleteType: ''
      }
    },
    computed: {
      ...mapGetters(['categories', 'tags']),
      parseUpdatedAt() {
        let date = new Date(this.editModel.updated_at)
        let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
        let month = date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1
        let year = date.getFullYear().toString().substr(-2, 2)
        let hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours()
        let minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()

        return day + '.' + month + '.' + year + ' @ ' + hours + ':' + minutes
      },
      confirmDeleteModalTitle () {
        return this.toDelete ? this.toDelete.name : ''
      },
      editTitle () {
        return this.$root.trans('blog_admin.categories_and_tags.edit_' + this.editingType.toLowerCase() + '_title')
      },
      editDescription () {
        return this.$root.trans('blog_admin.categories_and_tags.edit_' + this.editingType.toLowerCase() + '_description')
      },
      deleteTitle () {
        return this.$root.trans('blog_admin.categories_and_tags.delete_' + this.deleteType.toLowerCase() + '_title')
      },
      deleteDescription () {
        return this.$root.trans('blog_admin.categories_and_tags.delete_' + this.deleteType.toLowerCase() + '_description')
      }
    },
    methods: {
      displayErrors(error, errorType) {
        let errorObject = Object.assign({}, error)
        errorObject = errorObject.response.data.errors
        Object.keys(errorObject).forEach(key => {
          Vue.set(this.errors[errorType], key, errorObject[key])
        })
      },
      closeConfirmDeleteModal () {
        this.showConfirmDeleteModal = false
        setTimeout(() => {
          this.deleteType = ''
          this.toDelete = null
        }, 300)
      },
      confirmDelete () {
        if (this.deleteType === 'category') {
          this.$store.dispatch('deleteCategory', this.toDelete)
        } else if (this.deleteType === 'tag') {
          this.$store.dispatch('deleteTag', this.toDelete)
        }
        this.closeConfirmDeleteModal()
      },
      createCategory () {
        this.errors.category = []
        this.$store.dispatch('createCategory', this.formCategory)
          .then(response => {
            this.formCategory = { name: '', description: '' }
          })
          .catch(error => {
            this.displayErrors(error, 'category')
          })
      },
      deleteCategory (category) {
        this.toDelete = category
        this.deleteType = 'category'
        this.showConfirmDeleteModal = true
      },
      editCategory () {
        this.errors.edit = []
        this.$store.dispatch('editCategory', this.editModel)
          .then(response => {
            this.showEditingModal = false
            this.editModel = { title: '', description: '', updated_at: '' }
          })
          .catch(error => {
            this.displayErrors(error, 'edit')
          })
      },
      createTag () {
        this.errors.tag = []
        this.$store.dispatch('createTag', this.formTag)
          .then(response => {
            this.formTag = { name: '', description: '' }
          })
          .catch(error => {
            this.displayErrors(error, 'tag')
          })
      },
      deleteTag (tag) {
        this.toDelete = tag
        this.deleteType = 'tag'
        this.showConfirmDeleteModal = true
      },
      editTag () {
        this.edit = []
        this.$store.dispatch('editTag', this.editModel)
          .then(response => {
            this.showEditingModal = false
            this.editModel = { title: '', description: '', updated_at: '' }
          })
          .catch(error => {
            this.displayErrors(error, 'edit')
          })
      },
      toggleEdit (model, type) {
        this.editingType = type
        this.editModel = Object.assign({}, model)
        this.showEditingModal = true
      },
      submitEdit () {
        if (this.editingType.toLowerCase() === 'category') {
          this.editCategory()
        } else {
          this.editTag()
        }
      }
    }
  }

</script>

<style lang="scss" scoped>

.view-container {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
}

@media only screen and (max-width: 990px) {
  .view-container {
    flex-direction: column;
  }
}

</style>