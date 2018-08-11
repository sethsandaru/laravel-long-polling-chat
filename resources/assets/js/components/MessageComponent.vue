<template>
    <div class="msg_history">
        <div v-for="mess in messages"
             :class="{'incoming_msg': !isSender(mess.Name), 'outgoing_msg': isSender(mess.Name)}">
            <div v-if="mess.Type !== 'system'"
                 :class="{'received_msg': !isSender(mess.Name), 'sent_msg': isSender(mess.Name)}">
                <div :class="{'received_withd_msg': !isSender(mess.Name)}">
                    <p v-if="mess.Type === 'text'">{{mess.Message}}</p>
                    <img v-if="mess.Type === 'image'" :src="base_url + mess.Attachment" class="img-fluid img-attachment">
                    <span class="time_date">{{mess.Name}} | {{mess.CreatedDate}}</span>
                </div>
            </div>
            <div v-else>
                <p class="text-center" style="padding: 10px">{{mess.Message}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MessageComponent",
        props: ['messages'],
        methods: {
            isSender(name) {
                return name === this.$parent.name;
            }
        },
        computed: {
            base_url() {
                return base_url;
            }
        }
    }
</script>

<style scoped>
    .img-attachment {
        max-height: 400px;
    }
</style>