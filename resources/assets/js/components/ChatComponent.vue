<template>
    <div class="messaging">
        <div class="inbox_msg" v-if="!_.isEmpty(name)">
            <div class="mesgs">
                <message-component :messages="reversedMess"></message-component>

                <div class="type_msg">
                    <div class="input_msg_write">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <a href="javascript:void(0)" @click="sendAttachment">
                                        <i class="fa fa-image"></i>
                                    </a>
                                </span>
                            </div>
                            <input type="text" class="write_msg form-control"
                                   placeholder="Type a message"
                                   v-model="textMessage"
                                   @keyup.enter="postMess">
                        </div>
                        <button class="msg_send_btn" type="button" @click="postMess">
                            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                        </button>

                        <input type="file" style="display: none"
                               accept="image/jpeg, image/png, image/gif"
                               id="fileAttachment" @change="doUploadAttachment">
                    </div>
                </div>
            </div>
        </div>

        <div v-if="_.isEmpty(name)" class="card">
            <div class="card-body">
                <h3>Join chat room now</h3>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Your Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName"
                               placeholder="Your Name"
                               v-model="pre_name" @keyup.enter="setName">
                    </div>
                </div>

                <div class="text-center">
                    <button class="btn btn-primary" @click="setName">
                        Join now!
                    </button>
                </div>
            </div>
        </div>


        <p class="text-center top_spac">By Seth Phat - <a href="https://sethphat.com" target="_blank">https://SethPhat.com</a></p>

    </div>
</template>

<script>
    import MessageComponent from './MessageComponent';
    export default {
        name: "ChatComponent",
        components: {
            MessageComponent
        },
        data: () => ({
            pre_name: "",
            name: "",
            messages: [],
            textMessage: "",
        }),
        methods: {
            setName() {
                if (_.isEmpty(this.pre_name)) {
                    return;
                }
                // set name and start to retrieve...
                this.name = this.pre_name;

                // join room mess
                axios.get(base_url + "api/v1/Message/JoinRoom", {params: {Name: this.name}});

                // start to retrieve..
                this.retrieveNewMess();
            },
            retrieveNewMess() {
                // prepare data
                var params = {
                    CreatedDate: "",
                    Name: this.name
                };
                if (this.messages.length > 0) {
                    params.CreatedDate = this.messages[0].CreatedDate;
                }

                // get data
                var self = this;
                axios.get(base_url + "api/v1/Message/RetrieveNewMess", {params})
                    .then(result => {
                        // solving data
                        var data = result.data;
                        if (_.isEmpty(data) || !data.data) {
                            self.retrieveNewMess();// load again
                            return;
                        }

                        // have new mess, unshift to message.
                        _.each(data.data, (mess, index) => {
                            self.messages.unshift(mess);
                        });

                        self.retrieveNewMess();// load again
                    })
                    .catch(err => {
                        self.retrieveNewMess();// load again
                    });
            },
            postMess() {
                if (_.isEmpty(this.textMessage)) {
                    return;
                }

                // prepare post data
                var postData = {
                    Name: this.name,
                    Message: _.clone(this.textMessage)
                };

                // clear mess
                this.textMessage = "";

                // request api
                var self = this;
                axios.post(base_url + "api/v1/Message/PostText", postData)
                    .then(result => {
                        var data = result.data;
                        if (_.isEmpty(data) || !data.status) {
                            if (data.error) {
                                self.$toaster.error(data.error);
                            } else {
                                self.$toaster.error("Failed to post a new message");
                            }
                            return;
                        }

                        self.messages.unshift(data.message);
                        setTimeout(() => {
                            $(".msg_history").scrollTop(99999999); // scroll to bottom after append your posted mess
                        }, 200);
                    });
            },
            sendAttachment() {
                $("#fileAttachment").val(null).click();
            },
            doUploadAttachment(e) {
                var file = e.target.files[0];
                if (file.type.indexOf("image") < 0)
                {
                    $("#fileAttachment").val(null);
                    this.$toaster.error("Your selected file is not an image.");
                    return;
                }

                // do upload
                var postData = new FormData();
                postData.append("Name", this.name);
                postData.append("Image", file);

                var self = this;
                axios.post(base_url + "api/v1/Message/PostImage", postData, {headers: {'Content-Type': "multipart/form-data"}})
                    .then(result => {
                        var data = result.data;
                        if (_.isEmpty(data) || !data.status) {
                            if (data.error) {
                                self.$toaster.error(data.error);
                            } else {
                                self.$toaster.error("Failed to post a new message");
                            }
                            return;
                        }

                        self.messages.unshift(data.message);
                        setTimeout(() => {
                            $(".msg_history").scrollTop(99999999); // scroll to bottom after append your posted mess
                        }, 200);
                    })
                    .catch(err => {
                        $("#fileAttachment").val(null);
                        this.$toaster.error("Upload image failed, please try again.");
                    });
            },
        },
        computed: {
            _() {
                return _;
            },
            reversedMess() {
                return [...this.messages].reverse();
            }
        }
    }
</script>

<style scoped>

</style>