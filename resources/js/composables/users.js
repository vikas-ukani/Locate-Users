import { ref } from 'vue'

export default function useUsers() {
    const users = ref([])

    /**
     * Fetch Users from API.
     * @param {*} params
     */
    const fetchUsers = async (params = '') => {
        users.value = await fetch(`/api/users/${params}`).then(async res => await res.json())
    }
    return { users, fetchUsers }
}
