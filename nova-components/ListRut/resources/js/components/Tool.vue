<template>
    <div class="relative" id="tool" ref="tool">
        <heading class="mb-3">Listado de usuarios</heading>
        <p>
            El formato del archivo debe ser excel o csv. Descargar ejemplo
            <a :href="this.url + 'Ruts_para_encuesta.xlsx'">
                Aquí
            </a>
        </p>

        <card class="overflow-hidden">
            <div>
                <div
                    style="text-align: center; margin-top:20px; color:green;"
                    v-show="recipients"
                >
                    {{ recipients }}
                </div>
                <div
                    style="text-align: center; margin-top:20px; color:red;"
                    v-show="errors"
                >
                    {{ errors }}
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label
                            class="inline-block text-80 pt-2 leading-tight"
                            for="heading"
                            >Nombre</label
                        >
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <select
                            v-model="name"
                            id="name"
                            class="w-full form-control form-input form-input-bordered"
                        >
                            <option
                                v-for="userRutList in userRutLists"
                                :key="userRutList.id"
                                :value="userRutList.id"
                            >
                                {{ userRutList.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label
                            class="inline-block text-80 pt-2 leading-tight"
                            for="heading"
                            >Archivo .csv</label
                        >
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <input type="file" @change="processFile($event)" />
                    </div>
                </div>
            </div>
            <br /><br /><br /><br />
            <div class="bg-30 flex px-8 py-4">
                <button
                    class="btn btn-default btn-primary inline-flex items-center relative"
                    @click="sendfile"
                >
                    Enviar
                </button>
                <img
                    v-show="is_loadin"
                    v-bind:src="this.url + 'loading.gif'"
                    style="height: 80px;
                    width: 80px;
                    background-position: center;
                    border-radius:50%; 
                    margin-top:-20px"
                />
            </div>
        </card>
    </div>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
    components: {},
    data: () => ({
        name: "",
        recipients: 0,
        errors: "",
        someData: [],
        userRutLists: [],
        temp: "",
        is_loadin: 0,
        url: ""
    }),

    methods: {
        processFile(event) {
            this.myFile = event.target.files[0];
        },
        sendfile() {
            this.is_loadin = 1;
            if (!this.myFile) {
                this.errors = "El campo archivo .csv es obligatorioo";
                this.is_loadin = 0;
            }
            if (!this.name) {
                this.errors = "El campo Nombre es obligatorio";
                this.is_loadin = 0;
            }
            console.log("////");
            console.log(this.myFile.name.split(".").pop());
            console.log("////");
            if (
                this.myFile.name.split(".").pop() == "xlsx" ||
                this.myFile.name.split(".").pop() == "xlsm" ||
                this.myFile.name.split(".").pop() == "xlsb" ||
                this.myFile.name.split(".").pop() == "xltx" ||
                this.myFile.name.split(".").pop() == "xltm" ||
                this.myFile.name.split(".").pop() == "xlt" ||
                this.myFile.name.split(".").pop() == "xls" ||
                this.myFile.name.split(".").pop() == "xml" ||
                this.myFile.name.split(".").pop() == "xml" ||
                this.myFile.name.split(".").pop() == "xlam" ||
                this.myFile.name.split(".").pop() == "xla" ||
                this.myFile.name.split(".").pop() == "xlw" ||
                this.myFile.name.split(".").pop() == "XLR" ||
                this.myFile.name.split(".").pop() == "csv"
            ) {
                const formData = new FormData();
                const requestConfig = {
                    headers: { "content-type": "multipart/form-data" }
                };
                if (this.myFile != undefined) {
                    formData.append("file", this.myFile, this.myFile.name);
                } else {
                    formData.append("file", this.myFile);
                }
                formData.append("name", this.name);
                axios
                    .post("/nova-vendor/list-rut/send", formData, requestConfig)
                    .then(
                        response => {
                            this.is_loadin = 0;
                            this.errors = "";
                            this.recipients = response.data.data.message;
                            this.heerrorsading = "";
                        },
                        error => {
                            console.log(error.response);
                            this.is_loadin = 0;
                            this.recipients = "";
                            this.errors = error.response.data.data.message;
                        }
                    );
            } else {
                this.errors = "Archivo no compatible";
                this.is_loadin = 0;
                this.recipients = "";
            }
        },
        getUserRut() {
            this.isLoading = true;
            axios
                .get("/nova-vendor/list-rut/getUserRut")
                .then(data => data.data.data)
                .then(userRutLists => {
                    this.userRutLists = userRutLists;
                    this.isLoading = false;
                });
        }
    },

    created() {
        this.getUserRut();
    },
    mounted() {
        if (process.env.NODE_ENV == "production") {
            this.url = "https://apimobile.integrapps.cl/";
        } else {
            //this.url = "http://integra-api.devel/";
            this.url = "https://integra-api.dmeat.cl/";
        }
    }
};
</script>
