import Login from './components/Login';
import Register from './components/Register';
import Home from "./components/Home";
import LessonHome from "./components/Lesson/Home";
import LessonTasks from "./components/Lesson/Tasks";
import LessonPeople from "./components/Lesson/People";
import NotFound from "./components/NotFound.vue";

export default {
    mode: 'history',

    linkActiveClass: 'active',

    routes: [
        {
            path: '/',
            component: Home
        },
        {
            path: '/login',
            component: Login,
        },
        {
            path: '/register',
            component: Register
        },
        {
            name: 'LessonHome',
            path: '/lessons/:id',
            component: LessonHome,
        },
        {
            name: 'LessonTasks',
            path: '/lessons/:id/tasks',
            component: LessonTasks
        },
        {
            name: 'LessonPeople',
            path: '/lessons/:id/people',
            component: LessonPeople
        },
        {
            path: '*',
            component: NotFound
        },

    ]
}