


const scriptsInEvents = {

	async ["Onstart-E_Event6_Act4"](runtime, localVars)
	{
		var sukien_sku = runtime.globalVars.sukien_sku;
		runtime.globalVars.sukien_sku = sukien_sku ? parseInt(sukien_sku) : 0;
		// console.log(sukien_sku)
	},

	async Eapi_Event66_Act1(runtime, localVars)
	{
		let encrypted = CryptoJS.AES.encrypt(encodeURIComponent(localVars.smg), localVars.pass).toString()
		localVars.returnMsg=encrypted;
		
	},

	async Eapi_Event68_Act1(runtime, localVars)
	{
		let decr= CryptoJS.AES.decrypt(localVars.stringEnc, localVars.passw);
		localVars.returnMsg=decodeURIComponent(decr.toString(CryptoJS.enc.Utf8).replace(/\+/g, ' '));
	}

};

self.C3.ScriptsInEvents = scriptsInEvents;

