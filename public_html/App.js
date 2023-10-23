/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, {Component} from 'react';
import {StyleSheet, View,TouchableOpacity,Text,ScrollView,Image} from 'react-native';


export default class App extends Component<Props> {
  constructor(props){
    super(props);
    this.state={
      so:'0',
      temp:0,
      howcal:'+'
    }
  }
  nhapso(a){
    if(this.state.so==0) this.setState({so:a});
    else this.setState({so:this.state.so+a.toString()});
  }
  cal(a){
    this.setState({
      temp:parseInt(this.state.so),
      howcal:a,
      so:0
    });
  }
  total(){
    total =0;
    if(this.state.howcal=='+')
      total = parseInt(this.state.temp)+parseInt(this.state.so);
    else if(this.state.howcal=='-')
      total = parseInt(this.state.temp)-parseInt(this.state.so);
    else if(this.state.howcal=='x')
      total = parseInt(this.state.temp)*parseInt(this.state.so);
    else if(this.state.howcal==':')
      total = parseInt(this.state.temp)/parseInt(this.state.so);
    
    this.setState({
      so:'Tong la: '+total,
    });
  }
  format(){
    this.setState({
      temp:0,
      howcal:'+',
      so:0
    });
  }
  render() {
    return (
      <View style={styles.parent}>
        <View style={styles.top}>
          <Text style={styles.sizelg,styles.txtqua}>{this.state.so}</Text>
        </View>
        <View style={styles.rows}>
            <TouchableOpacity onPress={()=>{this.format();}} style={styles.col1}><Text style={styles.sizelg}>C</Text></TouchableOpacity>
            <TouchableOpacity style={styles.col2}><Text style={styles.sizelg}>+/-</Text></TouchableOpacity>
            <TouchableOpacity style={styles.col3}><Text style={styles.sizelg}>%</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.cal(':');}} style={styles.col4} style={styles.col4}><Text style={styles.sizelg}>:</Text></TouchableOpacity>
        </View>
        <View style={styles.rows}>
            <TouchableOpacity onPress={()=>{this.nhapso(7);}} style={styles.col1}><Text style={styles.sizelg}>7</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.nhapso(8);}} style={styles.col2}><Text style={styles.sizelg}>8</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.nhapso(9);}} style={styles.col3}><Text style={styles.sizelg}>9</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.cal('x');}} style={styles.col4}><Text style={styles.sizelg}>X</Text></TouchableOpacity>
        </View>
        <View style={styles.rows}>
            <TouchableOpacity onPress={()=>{this.nhapso(4);}} style={styles.col1}><Text style={styles.sizelg}>4</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.nhapso(5);}} style={styles.col2}><Text style={styles.sizelg}>5</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.nhapso(6);}} style={styles.col3}><Text style={styles.sizelg}>6</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.cal('-');}} style={styles.col4} style={styles.col4}><Text style={styles.sizelg}>-</Text></TouchableOpacity>
        </View>
        <View style={styles.rows}>
            <TouchableOpacity onPress={()=>{this.nhapso(1);}} style={styles.col1}><Text style={styles.sizelg}>1</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.nhapso(2);}} style={styles.col2}><Text style={styles.sizelg}>2</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.nhapso(3);}} style={styles.col3}><Text style={styles.sizelg}>3</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.cal('+');}} style={styles.col4} style={styles.col4}><Text style={styles.sizelg}>+</Text></TouchableOpacity>
        </View>
        <View style={styles.rows}>
            <TouchableOpacity onPress={()=>{this.nhapso(0);}} style={styles.coltwo}><Text style={styles.sizelg}>0</Text></TouchableOpacity>
            <TouchableOpacity style={styles.col3}><Text style={styles.sizelg}>.</Text></TouchableOpacity>
            <TouchableOpacity onPress={()=>{this.total();}} style={styles.col4}><Text style={styles.sizelg}>=</Text></TouchableOpacity>
        </View>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  sizelg:{
    fontSize:30,
  },
  txtqua:{
    color:'white',
    fontSize:50,
  },
  parent: {
    flex: 1,
    backgroundColor: '#333',
    flexDirection:"column",
  },
  top:{
    flex:3/7,
    justifyContent: 'flex-end',
    alignItems: 'flex-end',
    fontSize:50,
    paddingRight:10,
  },
  rows:{
    flexDirection:"row",
    flex: 1/7,
    borderTopWidth:1,
    borderLeftWidth:1,
  },
  col1:{
    backgroundColor:'#777',
    flex:1/4,
    justifyContent: 'center',
    alignItems: 'center',
    borderRightWidth:1,
  },
  col2:{
    backgroundColor:'#777',
    flex:1/4,
    justifyContent: 'center',
    alignItems: 'center',
    borderRightWidth:1,
  },
  col3:{
    backgroundColor:'#777',
    flex:1/4,
    justifyContent: 'center',
    alignItems: 'center',
      borderRightWidth:1,
  },
  col4:{
    backgroundColor:'yellow',
    flex:1/4,
    justifyContent: 'center',
    alignItems: 'center',
    borderRightWidth:1,
  },
  coltwo:{
    backgroundColor:'#777',
    flex:2/4,
    justifyContent: 'center',
    alignItems: 'center',
    borderRightWidth:1,
  }
});
