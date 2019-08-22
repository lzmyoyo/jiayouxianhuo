//app.js
//var apiUrl = 'http://101.37.67.41:81/api';
var serverUrl = 'http://dev.jiayouxianhuo.com';
var apiUrl = serverUrl + '/api';
var storageTokenKey = 'userTokenttttttttttttttttttttttttttttttttttttttttttttttttt';
var storageUserKey = 'userInfottttttttttttttttttttttttttt';
function login(callBack) {
  wx.login({
    success: res => {
      // 发送 res.code 到后台换取 openId, sessionKey, unionId
      wx.request({
        url: apiUrl + '/user/login',
        data: { code: res.code },
        success: function (loginResult) {
          if (loginResult.data.code == 200) {
            var dataInfo = loginResult.data.data;
            wx.setStorage({
              key: storageTokenKey,
              data: dataInfo.userInfo.userToken,
            });
            
            if (dataInfo.userInfo.nickName != '' && dataInfo.userInfo.avatarUrl != '') {
              wx.setStorage({
                key: storageUserKey,
                data: dataInfo.userInfo,
              });
              callBack(true, dataInfo.userInfo);
            } else {
              callBack(false, dataInfo.userInfo);
            }
          } else {
            console.log('errror');
          }
        }
      })
    }
  })
}



App({
  onLaunch: function () {
    var _self = this;
    //检查sessionkey是否失效，如果失效需要调用login，重新登录，登录成功后，userToken 重新赋值
    wx.checkSession({
      success() {
        wx.getStorage({
          key: _self.globalData.storageTokenKey,
          success(res) {


            console.log('token有效');

            _self.globalData.userToken = res.data;
            wx.getStorage({
              key: _self.globalData.storageUserKey,
              success: function(res) {
                if (res.data.nickName) {
                  _self.globalData.isScopeUserInfo = true;
                  _self.globalData.userInfo = res.data;
                } else{
                  _self.appGetUserInfo(function (userResult) {
                    if (userResult == '') {
                      _self.globalData.isScopeUserInfo = false;
                      _self.globalData.userInfo = '';
                    } else {
                      console.log('保存用户信息');
                      _self.setUserData(userResult);
                    }
                  });
                }
              },
              fail:function(fres){
                _self.appGetUserInfo(function (userResult) {
                  if (userResult == '') {
                    _self.globalData.isScopeUserInfo = false;
                    _self.globalData.userInfo = '';
                  } else {
                    console.log('保存用户信息');
                    _self.setUserData(userResult);
                  }
                });
              }
            })
          },
          fail(errorRes){

            console.log('token无效，本地token没有获取到');
            
            login(function (isScopeUserInfo,userInfo){
              if (userInfo.userToken != '') {
                console.log(333);
                _self.globalData.userToken = userInfo.userToken;
                _self.appGetUserInfo(function(userResult) {
                  if (userResult == '') {
                    _self.globalData.isScopeUserInfo = false;
                    _self.globalData.userInfo = '';
                  } else {
                    console.log('保存用户信息');
                    _self.setUserData(userResult);
                  }
                });
              } else {
                _self.globalData.isScopeUserInfo = false;
                _self.globalData.userInfo = '';
              }
            });
          }
        })
      },
      fail() {
        console.log('token无效');
        // session_key 已经失效，需要重新执行登录流程
        login(function (isScopeUserInfo, userInfo) {
          _self.globalData.isScopeUserInfo = isScopeUserInfo;
          _self.globalData.userInfo = userInfo;
        });
      }
    })

    /*
    // 获取用户信息
    wx.getSetting({
      success: res => {
        //判断是否授权获取用户信息
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          _self.globalData.isScopeUserInfo = true;
          wx.getUserInfo({
            success: res => {
              // 可以将 res 发送给后台解码出 unionId
              _self.globalData.userInfo = res.userInfo

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallbackTo) {
                this.userInfoReadyCallbackTo(res)
              }
            }
          });
        } else {
          _self.globalData.isScopeUserInfo = false;
        }
      }
    })
    */

  },
  setUserData: function (userInfo,callBack = '') {
    console.log('保存用户信息6');
    var _self = this;
    wx.request({
      url: _self.globalData.apiUrl + '/user/saveUserInfo',
      data: { userInfo: userInfo, userToken: _self.globalData.userToken },
      method: 'post',
      success: function (res) {
        var dataResult = res.data;
        if (dataResult.code == 200) {
          if (dataResult.data.userInfo.nickName == undefined) {
            login(function (isScopeUserInfo, userInfo) {
              _self.globalData.isScopeUserInfo = isScopeUserInfo;
              _self.globalData.userInfo = userInfo;
            });
          } else {
            console.log('保存成功');
            _self.globalData.isScopeUserInfo = true;
            _self.globalData.userInfo = dataResult.data.userInfo;
            wx.setStorage({
              key: _self.globalData.storageUserKey,
              data: dataResult.data.userInfo,
            });
          }
          if (callBack != undefined && callBack != '') {
            callBack();
          }
        }
      }
    })
  },
  appGetUserInfo:function(callBack) {
    wx.getSetting({
      success: res => {
        //判断是否授权获取用户信息
        console.log(res);
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          
          wx.getUserInfo({
            success: res => {
              console.log('已得到授权');
              // 可以将 res 发送给后台解码出 unionId
              callBack(res.userInfo);
            }
          });
        } else {
          console.log('未得到授权');
          callBack('');
        }
      }
    })
  },
  isLogin:function() {  //其他页面可以调用，app.js 不能调用。因为涉及到初始化数据。
    var isScopeUserInfo = this.globalData.isScopeUserInfo;
    if (!isScopeUserInfo) {
      return true;
    } else {
      return false;
    }
  },
  toLogin:function(pageName) {
    wx.redirectTo({
      url: pageName,
    });
  },
  appTokenRequest:function(url,data,success,fail = '') {
    data.userToken = this.globalData.userToken;
    wx.request({
      url: this.globalData.apiUrl + url,
      data:data,
      method: 'post',
      success:function(res){
        success(res);
      },
      fail:function(failRes) {
        if(fail != undefined) {
          fail(failRes);
        }
      }
    })
  },
  globalData: {
    storageTokenKey: storageTokenKey,
    storageUserKey: storageUserKey,
    userInfo: null,
    isScopeUserInfo:false,
    userToken:'',
    serverUrl: serverUrl,
    apiUrl:apiUrl
  }
})