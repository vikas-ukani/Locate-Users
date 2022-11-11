<template>

    <div class="pt-4">
        <div
            class="border-2 shadow-xl bg-gray-200 border-gray-400 rounded-xl py-5  px-5 mx-5 grid grid-flow-row grid-cols-2 gap-5 pt-4 ">
            <div>
                <label for="first_name" class="block text-sm font-bold ">First Name</label>
                <div class="mt-1">
                    <input type="text" name="first_name" id="first_name"
                        class="block mt-1 w-full h-12 rounded-xl border px-2 border-gray-300 shadow-sm focus:border-gray-300 focus:ring focus:ring-graborder-gray-200 focus:ring-opacity-50"
                        placeholder="Please enter first name to search."
                        v-model="filter.first_name">
                </div>
            </div>
            <div>
                <label for="last_name" class="block text-sm font-bold ">Last Name</label>
                <div class="mt-1">
                    <input type="text" name="last_name" id="last_name"
                        class="block mt-1 w-full h-12 rounded-xl border px-2 border-gray-300 shadow-sm focus:border-gray-300 focus:ring focus:ring-graborder-gray-200 focus:ring-opacity-50"
                        placeholder="Please enter last name to search."
                        v-model="filter.last_name">
                </div>
            </div>

            <div class="">
                <label class="font-extrabold">Gender</label>
                <div class="flex items-center gap-2">
                    <div>
                        <label for="male" class="cursor-pointer">Male </label>
                        <input id="male" type="radio" v-model="filter.gender" name="gender" value="Male" />
                    </div>
                    <div>
                        <label for="female" class="cursor-pointer">Female </label>
                        <input id="female" type="radio" v-model="filter.gender" name="gender" value="Female" />
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="button" @click="appyFilter"
                    class="inline-flex items-center justify-center h-12 px-6 font-medium tracking-wide text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-800 focus:shadow-outline focus:outline-none">
                    Filter</button>
                <button type="reset" @click="clearFilter"
                    class="inline-flex items-center justify-center h-12 px-6 font-medium tracking-wide text-white transition duration-200 bg-gray-900 rounded-lg hover:bg-gray-800 focus:shadow-outline focus:outline-none">
                    Clear
                </button>
            </div>
        </div>

        <div v-if="loading">
            <div class="h-screen bg-transparent ">
                <div class="flex justify-center h-full mt-40">
                    <img class="h-56 w-56 bg-transparent "
                        src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/35771931234507.564a1d2403b3a.gif"
                        alt="">
                </div>
            </div>
        </div>
        <div v-else class="mt-5 flex ">
            <div class="w-1/6 px-1 pl-2 mt-2">
                <b class="underline">All Users</b>
                <ul v-if="users.length" class="pl-1 mt-2">
                    <li v-for="(user, index) in users" :key="user.id"
                    class="py-1 hover:font-bold cursor-pointer hover:duration-200">
                        {{ ++index }}. {{ user.first_name }}
                    </li>
                </ul>
                <div v-else> No User available.</div>
            </div>
            <div class="w-5/6">
                <GoogleMap ref="mapRef" v-on:dragend="changeMap" v-if="users || loading === false"
                    api-key="AIzaSyA2buBJTO5REpeBy5lqEWgbIzaOh9kO0gY" style="width: 100%; height: 700px" :zoom="5"
                    :center="center">
                    <template #default="{ ready, api, map, mapTilesLoaded }">
                        {{ mapTilesLoaded }}
                        <Marker v-if="users" v-for="(user, index) in users" :options="{
                            position: { lat: user?.lat, lng: user.lon },
                            icon: user.gender === 'Female' ? 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' : 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                            scaledSize: 1000
                        }" :key="index">
                            <InfoWindow>
                                <div id="contet">
                                    <div id="siteNotice">
                                        <h1 class="firstHeading font-bold text-lg text-red-600">
                                            {{ user.first_name }} {{ user.last_name }}
                                        </h1>
                                    </div>
                                    <div id="bodyContent" class="w-full text-justify p-1 text-md">
                                        <p class="flex justify-between mb-2"> <span>Gender: </span><span
                                                class="font-extrabold">{{ user.gender }}</span></p>
                                        <p class="flex justify-between mb-2">
                                            <span>Latitude:</span>
                                        <div class="font-extrabold"> {{ user.lat }}</div>
                                        </p>
                                        <p class="flex justify-between space-y-2">
                                            <span>Longitude:</span>
                                        <div class="font-extrabold"> {{ user.lon }}</div>
                                        </p>
                                    </div>
                                </div>
                            </InfoWindow>
                        </Marker>
                    </template>
                </GoogleMap>
            </div>
        </div>
    </div>
</template>

<script >
import { defineComponent, ref, onMounted, reactive } from "vue";
import { CustomMarker, GoogleMap, InfoWindow, Marker, MarkerCluster } from "vue3-google-map";
import useUsers from './composables/users'


export default defineComponent({
    components: { GoogleMap, Marker, InfoWindow, CustomMarker, MarkerCluster },
    setup() {
        const center = { lat: 40.689247, lng: -74.044502 };
        const mapRef = ref(null)
        const loading = ref(false)
        const { users, fetchUsers } = useUsers()

        // Filter Object
        const filter = reactive({
            gender: null,
            first_name: null,
            last_name: null,
            lat: 40.689247,
            lon: -74.044502,
        })


        // MOUNTING
        onMounted(async () => {
            loading.value = true
            setTimeout(() => {
                loading.value = false
                appyFilter()
            }, 1000);
        })

        // METHODS
        const appyFilter = async () => {
            let params = {}
            let url = ''
            for (let i in filter) {
                if (filter[i]) params[i] = filter[i]
            }
            if (params) url = `?${new URLSearchParams(params).toString()}`
            await fetchUsers(url)
        }
        const clearFilter = async () => {
            filter.first_name = ''
            filter.last_name = ''
            filter.gender = ''
            appyFilter()
        }

        const changeMap = () => {
            let lat = mapRef.value.map.getCenter()?.lat(),
                lon = mapRef.value.map.getCenter()?.lng()
            filter.lat = lat
            filter.lon = lon
            appyFilter()
        }

        return {
            mapRef,
            loading,
            users,
            center,
            filter,
            changeMap,
            clearFilter,
            appyFilter,
        };
    },
});
</script>
