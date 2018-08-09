<template>
    <div class="msg_history">
        <div v-for="mess in messages"
             :class="{'incoming_msg': !isSender(mess.Name), 'outgoing_msg': isSender(mess.Name)}">
            <div :class="{'received_msg': !isSender(mess.Name), 'sent_msg': isSender(mess.Name)}">
                <div :class="{'received_withd_msg': !isSender(mess.Name)}">
                    <p v-if="mess.Type === 'text'">{{mess.Message}}</p>
                    <img v-if="mess.Type === 'image'" src="" class="img-fluid img-attachment">
                    <span class="time_date">{{mess.Name}} | {{mess.CreatedDate}}</span></div>
            </div>
        </div>
        <!--<div class="outgoing_msg">-->
            <!--<div class="sent_msg">-->
                <!--<p>Test which is a new approach to have all-->
                    <!--solutions</p>-->
                <!--<span class="time_date"> 11:01 AM    |    June 9</span> </div>-->
        <!--</div>-->
        <!--<div class="incoming_msg">-->
            <!--<div class="received_msg">-->
                <!--<div class="received_withd_msg">-->
                    <!--<p>Test, which is a new approach to have</p>-->
                    <!--<span class="time_date"> 11:01 AM    |    Yesterday</span></div>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="outgoing_msg">-->
            <!--<div class="sent_msg">-->
                <!--<p>Apollo University, Delhi, India Test</p>-->
                <!--<span class="time_date"> 11:01 AM    |    Today</span> </div>-->
        <!--</div>-->
        <!--<div class="incoming_msg">-->
            <!--<div class="received_msg">-->
                <!--<div class="received_withd_msg">-->
                    <!--<p>We work directly with our designers and suppliers,-->
                        <!--and sell direct to you, which means quality, exclusive-->
                        <!--products, at a price anyone can afford.</p>-->
                    <!--<span class="time_date"> 11:01 AM    |    Today</span></div>-->
            <!--</div>-->
        <!--</div>-->
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
    }
</script>

<style scoped>
    .img-attachment {
        max-height: 400px;
    }
</style>