<template>
    <div class="relative" id="tool" ref="tool">
        <heading class="mb-3">Notificaciones</heading>

        <card class="overflow-hidden">
            <div>
                <div style="text-align: center; margin-top:20px; color:green;" v-show="recipients">{{ recipients }}</div>
                <div style="text-align: center; margin-top:20px; color:red;">
                    <div v-if="errors.length">
                        <div v-for="error in errors">{{ error }}</div>
                    </div>
                </div>


                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="text">Fecha y hora</label>
                    </div>

                    <div class="py-6 px-8 w-1/2">
                        <date-picker v-model="datetime" type="datetime"></date-picker>
                    </div>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="heading">Nombre</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <input v-model="heading" :id="heading" :class="{ 'border-danger': hasError }" type="text" class="w-full form-control form-input form-input-bordered" required>
                        <p v-if="hasErrorHead" class="text-xs mt-2 text-danger">{{ firstErrorHead }}</p>
                    </div>
                </div>

                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="heading">Tipo</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <select v-model="type" id="type" class="w-full form-control form-input form-input-bordered">
                            <option v-bind:value ="1"> Liquidación de sueldo </option>
                            <option v-bind:value ="2"> Noticias  </option>
                            <option v-bind:value ="3"> Comunicados </option>
                            <option v-bind:value ="4"> Ofertas laborales </option>
                        </select>
                    </div>
                </div>

                <div v-if="type==2">
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight" for="heading">Noticias</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                        <select v-model="post" id="post" class="w-full form-control form-input form-input-bordered">
                            <option v-for="post in posts" :key="post.id" :value="post.id">
                                {{ post.title }}
                            </option>
                        </select>
                        </div>
                    </div>
                </div>

                <div v-if="type==4">
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight" for="heading">Ofertas laborales</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                        <select v-model="jobOffer" id="post" class="w-full form-control form-input form-input-bordered">
                            <option v-for="jobOffer in jobOffers" :key="jobOffer.id" :value="jobOffer.id">
                                {{ jobOffer.title }}
                            </option>
                        </select>
                        </div>
                    </div>
                </div>

                <div v-if="type==3">
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight" for="heading">Comunicados</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                        <select v-model="release" id="release" class="w-full form-control form-input form-input-bordered">
                            <option v-for="release in releases" :key="release.id" :value="release.id">
                                {{ release.title }}
                            </option>
                        </select>
                        </div>
                    </div>
                </div>

                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="text">Descripción</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <textarea
                                v-model="text"
                                :id="text"
                                :class="{ 'border-danger': hasError }"
                                class="w-full form-control form-input form-input-bordered py-3 h-auto"
                        ></textarea>

                        <p v-if="hasError" class="text-xs mt-2 text-danger">{{ firstError }}</p>
                    </div>
                </div>

                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="heading">Cargo</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                     <multiselect  v-model="valuePosition" :multiple="true" :options="positions" :searchable="true" :close-on-select="false" :show-labels="true" placeholder="Seleccione el cargo"></multiselect>
                    </div>
                </div>

                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="heading">Dependencia</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                      <multiselect v-model="valueDistrict" :id="valueDistrict" :multiple="true" :options="districts" :searchable="true" :close-on-select="false" :show-labels="true" placeholder="Seleccione la dependencia"></multiselect>
                        <p v-if="hasError" class="text-xs mt-2 text-danger">{{ firstError }}</p>

                    </div>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="heading">Region</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                     <multiselect v-model="valueRegion" :multiple="true" :options="regions" :searchable="true" :close-on-select="false" :show-labels="true" placeholder="Seleccione la región"></multiselect>
                    </div>
                </div>
            </div>
            <br><br><br><br>
            <div class="bg-30 flex px-8 py-4">
                <button class="btn btn-default btn-primary inline-flex items-center relative" @click="sendPushNotification">Enviar</button>
            </div>
        </card>
    </div>
</template>

<script>
    import { Errors } from 'laravel-nova'
    import Multiselect from 'vue-multiselect'
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';

    export default {
        components: { Multiselect, DatePicker },
        data: () => ({
            heading: "",
            text: "",
            url: "",
            type: "",
            date: "",
            errors: [],
            releases:[],
            hasErrorHead:[],
            districts: [],
            posts:[],
            regions:[],
            positions:[],
            recipients: 0,
            formErrors: new Errors(),
            valueRegion: null,
            valueDistrict: null,
            valuePosition: null,
            datetime: null,
            options: ['Select option', 'options', 'selected', 'mulitple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched']

        }),

        computed: {
            hasError() {
                return this.formErrors.has('text');
            },

            hasErrorHead() {
                return this.formErrors.has('heading');
            },

            firstError() {
                if (this.hasError) {
                    return this.formErrors.first('text');
                }
            },

            firstErrorHead() {
                if (this.hasError) {
                    return this.formErrors.first('heading');
                }
            },
        },

        methods: {
            sendPushNotification() {
                console.log(this);
                this.formErrors = new Errors();
                Nova.request().post('/nova-vendor/nova-push-notification/send', {
                    heading: this.heading,
                    text: this.text,
                    districts: this.valueDistrict,
                    positions: this.valuePosition,
                    regions: this.valueRegion,
                    type: this.type,
                    post_id: this.post,
                    release_id: this.release,
                    jobOffer_id: this.jobOffer,
                    datetime: this.datetime,
                }).then(response => {
                    //this.$refs.toolbarChat.scrollTop = 0;
                    this.scrollTo();
                    if(response.status==200){
                        this.errors = [];
                        this.recipients = response.data.data;
                        this.heading = '';
                        this.datetime = '';
                        this.text = '';
                        this.type = '';
                        this.districts = '';
                        this.positions = '';
                        this.regions = '';
                        this.post = '';
                        this.release_id = '';
                        this.jobOffer_id = '';
                    }
                }).catch(e => {
                    this.errors = [];
                    this.formErrors = new Errors(e.response.data.error)
                    this.scrollTo();
                    if(e.response.data.massage){
                        this.errors.push(e.response.data.massage);
                    }
                    console.log(e.response.data.massage);
                    if(e.response.data.heading!=undefined){
                        this.errors.push(e.response.data.heading[0]);
                    }
                    if(e.response.data.datetime!=undefined){
                        this.errors.push(e.response.data.datetime[0]);
                    }
                    if(e.response.data.type !=undefined){
                        this.errors.push(e.response.data.type[0]);
                    }
                    if(e.response.data.text !=undefined){
                        this.errors.push(e.response.data.text[0]);
                    }
                });
            },

            getDistricts() {
                Nova.request().get('/nova-vendor/nova-push-notification/districts')
                    .then(data => data.data.data)
                    .then((districts) => {
                        this.districts = districts;
                        console.log(districts);
                    });
            },

            getPositions() {
                Nova.request().get('/nova-vendor/nova-push-notification/positions')
                    .then(data => data.data.data)
                    .then((positions) => {
                        this.positions = positions;
                        console.log(positions);
                    });
            },
            getRegions() {
                Nova.request().get('/nova-vendor/nova-push-notification/regions')
                    .then(data => data.data.data)
                    .then((regions) => {
                        this.regions = regions;
                        console.log(regions);
                    });
            },

            getPost() {
                Nova.request().get('/nova-vendor/nova-push-notification/posts')
                    .then(data => data.data.data)
                    .then((posts) => {
                        this.posts = posts;
                        console.log(posts);
                    });
            },
            scrollTo(){
                    var element = document.getElementById("tool");
                    var top = element.offsetTop;
                    window.scrollTo(0, top);
            },

            getReleases() {
                Nova.request().get('/nova-vendor/nova-push-notification/releases')
                    .then(data => data.data.data)
                    .then((releases) => {
                        this.releases = releases;
                        console.log(releases);
                    });
            },

            jobOffers() {
                Nova.request().get('/nova-vendor/nova-push-notification/jobOffers')
                    .then(data => data.data.data)
                    .then((jobOffers) => {
                        this.jobOffers = jobOffers;
                        console.log(jobOffers);
                    });
            },
        },

        created() {
            this.getDistricts();
            this.getPositions();
            this.getRegions();
            this.getPost();
            this.getReleases();
            this.jobOffers();
        },
    }
</script>