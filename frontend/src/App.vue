<template>
  <Card class="card-box">
    <p slot="title">
      <Icon type="ios-chatboxes"></Icon>
      ..uu..
    </p>
    <a href="#" slot="extra" @click.prevent="changeLimit">
      <Icon type="md-close" />
    </a>
    <div class="split-container">
      <div class="split-box" :style="boxHeight">
        <Split v-model="split2" mode="vertical">
          <div slot="top">
            <div class="message" v-for="msg in messageList" v-bind:key="msg">
              <div class="demo-avatar">
                <Avatar icon="ios-person" />
                <Tag checkable color="primary">{{msg.message}}</Tag>
              </div>
            </div>
          </div>
          <div slot="bottom">
            <Input class="input-box" v-model="message" type="textarea" :autosize="autoSize" placeholder=""></Input>
            <Button class="submit-button" type="primary" @click="handleSubmit()">Signin</Button>
          </div>
        </Split>
      </div>
    </div>
  </Card>
</template>
<script>
export default {
  methods: {
    ajustHeight () {
      return window.outerHeight - this.titleHeight
    },
    handleSubmit () {
      var data = {
        'room_id': this.roomID,
        'user_name': '',
        'message': this.message,
        'timestamp': Math.round(new Date().getTime() / 1000)
      }
      this.message = ''
      this.ws.send(JSON.stringify(data))
    }
  },
  data () {
    return {
      roomID: 10086,
      titleHeight: 200,
      message: '',
      messageList: [],
      split2: 0.7,
      boxHeight: {
        height: this.ajustHeight() + 'px'
      },
      autoSize: {
        minRows: 5,
        maxRows: 5
      },
      ws: null
    }
  },
  beforeMount () {
    this.boxHeight = {
      height: this.ajustHeight() + 'px'
    }
  },
  mounted () {
    this.ws = new WebSocket('ws://127.0.0.1:9502/room/' + this.roomID)
    this.ws.onopen = function () {
      console.log('onopen')
    }

    this.ws.onerror = function (err) {
      console.log(err)
    }

    this.ws.onmessage = function (evt) {
      var data = JSON.parse(evt.data)
      this.messageList.push(data)
    }.bind(this)

    this.ws.onclose = function () {
      console.log('onclose')
    }
  }
}
</script>
<style>
.card-box {
  border: 10px solid #eee;
  box-shadow: 10px 10px 5px #eee;
  border-radius: 15px;
}
.split-container {
  margin: -16px;
  background: #eee;
}
.split-box {
  background: #fff;
}
.submit-button {
  position: fixed;
  bottom: 60px;
  right: 30px;
}
textarea {
  border: 0 !important;
  outline: none !important;
  resize: none;
}
.message {
  padding: 10px;
}
</style>
