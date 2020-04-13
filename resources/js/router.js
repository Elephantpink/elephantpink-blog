import Authors from './views/Authors'
import Posts from './views/Posts'
import PostDetails from './views/PostDetails'
import CategoriesAndTags from './views/CategoriesAndTags'

const blogAdminRoutes = [
    {
        name: 'admin-authors',
        path: '/authors',
        component: Authors,
        meta: {
            adminRoute: true
        }
    },
    {
        name: 'admin-categories-tags',
        path: '/categories-tags',
        component: CategoriesAndTags,
        meta: {
            adminRoute: false
        }
    },
    {
        name: 'admin-posts',
        path: '/posts',
        component: Posts,
        meta: {
            adminRoute: false
        }
    },
    {
        name: 'admin-post-create',
        path: '/posts/create',
        component: PostDetails,
        meta: {
            adminRoute: false
        }
    },
    {
        name: 'admin-post-edit',
        path: '/posts/edit/:slug',
        component: PostDetails,
        meta: {
            adminRoute: false
        }
    },
]

export default blogAdminRoutes