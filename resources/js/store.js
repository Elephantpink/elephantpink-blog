import axios from 'axios'
import Vue from 'vue'

let blogStore = {
    state: {
        blogAPI: '/api/v1/blog/',
        authors: [],
        categories: [],
        posts: [],
        tags: [],
    },
    getters: {
        blogAPI: state => { return state.blogAPI },
        authors: state => { return state.authors },
        categories: state => {
            return state.categories.sort((a, b) => {
                return a.name.toLowerCase() > b.name.toLowerCase() ? 1 : -1
            })
        },
        posts: state => { return state.posts },
        tags: state => {
            return state.tags.sort((a, b) => {
                return a.name.toLowerCase() > b.name.toLowerCase() ? 1 : -1
            })
        },
        getAuthorById: (state) => (id) => {
            let author = (state.authors).filter(a => { return a.id === id })
            return author.length ? author[0] : { name: '' }
        },
        getCategoryById: (state) => (id) => {
            let category = (state.categories).filter(c => { return c.id === id })
            return category.length ? category[0] : { name: 'Category not found' }
        },
        getPostById: (state) => (id) => {
            let post = (state.posts).filter(p => { return p.id === id })
            return post.length ? post[0] : null
        },
        getPostBySlug: (state) => (slug) => {
            let post = state.posts.filter(p => { return p.slug === slug })
            return post.length ? post[0] : null
        },
        getTagById: (state) => (id) => {
            let tag = state.tags.filter(t => { return t.id === id })
            return tag.length ? tag[0] : { name: 'Tag not found' }
        },
    },
    mutations: {
        // AUTHORS
        setAuthors(state, authors) {
            state.authors = authors
        },
        addAuthor(state, author) {
            state.authors.push(author)
        },
        deleteAuthor(state, author) {
            state.authors.splice(state.authors.indexOf(author), 1)
        },
        updateAuthor(state, updatedAuthor) {
            let author = state.authors.filter(a => { return a.id == updatedAuthor.id })

            if (author.length) {
                Vue.set(state.authors, state.authors.indexOf(author[0]), updatedAuthor)
            }
        },
        // CATEGORIES
        setCategories(state, categories) {
            state.categories = categories
        },
        addCategory(state, category) {
            state.categories.push(category)
        },
        deleteCategory(state, category) {
            state.categories.splice(state.categories.indexOf(category), 1)
        },
        updateCategory(state, updatedCategory) {
            let category = state.categories.filter(c => { return c.id == updatedCategory.id })

            if (category.length) {
                Vue.set(state.categories, state.categories.indexOf(category[0]), updatedCategory)
            }
        },
        // POSTS
        setPosts(state, posts) {
            state.posts = posts
        },
        addPost(state, post) {
            state.posts.push(post)
        },
        deletePost(state, post_id) {
            let post = (state.posts).filter(p => { return p.id === post_id })[0]
            state.posts.splice(state.posts.indexOf(post), 1)
        },
        updatePost(state, updatedPost) {
            let post = state.posts.filter(p => { return p.id == updatedPost.id })

            if (post.length) {
                Vue.set(state.posts, state.posts.indexOf(post[0]), updatedPost)
            }
        },
        removePostsCategory(state, { postsIds, category }) {
            let posts = state.posts.filter(p => { return postsIds.indexOf(p.id) > -1 })

            posts.forEach(post => {
                post.categories.splice(post.categories.indexOf(category.id), 1)
            })
        },
        removePostsTag(state, { postsIds, tag }) {
            let posts = state.posts.filter(p => { return postsIds.indexOf(p.id) > -1 })

            posts.forEach(post => {
                post.tags.splice(post.tags.indexOf(tag.id), 1)
            })
        },
        // TAGS
        setTags(state, tags) {
            state.tags = tags
        },
        addTag(state, tag) {
            state.tags.push(tag)
        },
        deleteTag(state, tag) {
            state.tags.splice(state.tags.indexOf(tag), 1)
        },
        updateTag(state, updatedTag) {
            let tag = state.tags.filter(t => { return t.id == updatedTag.id })

            if (tag.length) {
                Vue.set(state.tags, state.tags.indexOf(tag[0]), updatedTag)
            }
        },
    },
    actions: {
        // AUTHORS
        getAuthors({ commit, getters }) {
            return axios.get(getters.blogAPI + 'authors')
                .then(response => {
                    commit('setAuthors', response.data.authors)
                })
                .catch(error => {
                    console.error(error)
                })
        },
        createAuthor({ commit, getters }, author) {
            return new Promise((resolve, reject) => {
                axios.post(getters.blogAPI + 'authors', author)
                    .then(response => {
                        commit('addAuthor', response.data.author)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        editAuthor({ commit, getters }, author) {
            return new Promise((resolve, reject) => {
                axios.put(getters.blogAPI + 'authors/' + author.id, author)
                    .then(response => {
                        commit('updateAuthor', response.data.author)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        deleteAuthor({ commit, getters }, author) {
            return new Promise((resolve, reject) => {
                axios.delete(getters.blogAPI + 'authors/' + author.id)
                    .then(response => {
                        commit('deleteAuthor', author)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        // CATEGORIES
        getCategories({ commit, getters }) {
            return axios.get(getters.blogAPI + 'categories')
                .then(response => {
                    commit('setCategories', response.data.categories)
                })
                .catch(error => {
                    console.error(error)
                })
        },
        createCategory({ commit, getters }, category) {
            return new Promise((resolve, reject) => {
                axios.post(getters.blogAPI + 'categories', category)
                    .then(response => {
                        commit('addCategory', response.data.category)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        editCategory({ commit, getters }, category) {
            return new Promise((resolve, reject) => {
                axios.put(getters.blogAPI + 'categories/' + category.id, category)
                    .then(response => {
                        commit('updateCategory', response.data.category)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        deleteCategory({ commit, getters }, category) {
            return new Promise((resolve, reject) => {
                axios.delete(getters.blogAPI + 'categories/' + category.id)
                    .then(response => {
                        const postsIds = response.data.category_posts
                        commit('removePostsCategory', { postsIds, category })
                        commit('deleteCategory', category)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        // POSTS
        getPosts({ commit, getters }) {
            return axios.get(getters.blogAPI + 'posts')
                .then(response => {
                    commit('setPosts', response.data.posts)
                })
                .catch(error => {
                    console.error(error)
                })
        },
        publishPost({ commit, getters }, data) {
            return new Promise((resolve, reject) => {
                axios.post(getters.blogAPI + 'post/publish', data)
                    .then(response => {
                        commit('addPost', response.data.post)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        editPost({ commit, getters }, data) {
            let formData = new FormData()
            formData.append("post", JSON.stringify(data.post))
            formData.append("categories", data.post.categories)
            formData.append("tags", data.post.tags)
            formData.append("thumbnail", data.thumbnail)
            formData.append("header", data.header)
            return new Promise((resolve, reject) => {
                axios.post(getters.blogAPI + 'post/' + data.post.id + '/edit', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then(response => {
                        commit('updatePost', response.data.post)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        deletePost({ commit, getters }, post) {
            return new Promise((resolve, reject) => {
                axios.delete(getters.blogAPI + 'post/' + post.id + '/delete')
                    .then(response => {
                        commit('deletePost', post.id)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        // TAGS
        getTags({ commit, getters }) {
            return axios.get(getters.blogAPI + 'tags')
                .then(response => {
                    commit('setTags', response.data.tags)
                })
                .catch(error => {
                    console.error(error)
                })
        },
        createTag({ commit, getters }, tag) {
            return new Promise((resolve, reject) => {
                axios.post(getters.blogAPI + 'tags', tag)
                    .then(response => {
                        commit('addTag', response.data.tag)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        editTag({ commit, getters }, tag) {
            return new Promise((resolve, reject) => {
                axios.put(getters.blogAPI + 'tags/' + tag.id, tag)
                    .then(response => {
                        commit('updateTag', response.data.tag)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        deleteTag({ commit, getters }, tag) {
            return new Promise((resolve, reject) => {
                axios.delete(getters.blogAPI + 'tags/' + tag.id)
                    .then(response => {
                        const postsIds = response.data.tag_posts
                        commit('removePostsTag', { postsIds, tag })
                        commit('deleteTag', tag)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
    }
}

export default blogStore