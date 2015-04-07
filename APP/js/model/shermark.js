app.factory('shermarkModel', function(){
	var model = {
		all : [
                        {
                            "id": 0,
                            "name": "Vitricomp",
                            "resume": "Duis non ea ex reprehenderit veniam qui irure exercitation do eiusmod ullamco.",
                            "content": "Ex sint in in qui veniam laborum aliqua cupidatat nisi minim veniam amet. Eu ex ea minim eu. Occaecat non minim ipsum cillum non pariatur culpa. Minim id eiusmod aliquip ut sunt eu aute eiusmod nostrud dolor mollit amet. Do laborum proident ipsum officia laborum exercitation culpa magna. Adipisicing aliqua proident Lorem tempor occaecat nulla et.",
                            "tag": [
                                {
                                    "value": "Bolax"
                                },
                                {
                                    "value": "Nexgene"
                                },
                                {
                                    "value": "Quonk"
                                },
                                {
                                    "value": "Velity"
                                },
                                {
                                    "value": "Furnafix"
                                },
                                {
                                    "value": "Intradisk"
                                },
                                {
                                    "value": "Eclipsent"
                                }
                            ]
                        },
                        {
                            "id": 1,
                            "name": "Ronelon",
                            "resume": "Nisi aliqua amet dolor ea ex laborum aliqua eiusmod ad veniam cupidatat enim officia anim.",
                            "content": "Dolore non esse et id Lorem consectetur deserunt cupidatat reprehenderit commodo non sint amet. Reprehenderit ipsum ex deserunt eu nostrud excepteur nisi laboris id in ad do. Aute culpa aute ea pariatur adipisicing mollit commodo. Reprehenderit sunt aliquip culpa excepteur consequat enim labore reprehenderit reprehenderit aute magna proident. Fugiat Lorem dolore id velit. Aute cillum Lorem esse pariatur nulla minim id sit exercitation do eiusmod dolor cupidatat eiusmod.",
                            "tag": [
                                {
                                    "value": "Imkan"
                                },
                                {
                                    "value": "Elpro"
                                },
                                {
                                    "value": "Digifad"
                                },
                                {
                                    "value": "Visualix"
                                },
                                {
                                    "value": "Aquamate"
                                },
                                {
                                    "value": "Jimbies"
                                }
                            ]
                        },
                        {
                            "id": 2,
                            "name": "Emtrak",
                            "resume": "Commodo nostrud occaecat deserunt quis enim ullamco laboris elit ipsum et velit excepteur.",
                            "content": "Incididunt enim culpa ullamco pariatur. Sunt cillum laboris laboris ipsum. Duis amet ex elit ad anim magna elit dolore reprehenderit amet eu. Id culpa sit sunt nostrud duis incididunt tempor nostrud ad ullamco eiusmod. Ad eiusmod ea ad anim culpa cillum labore non consequat laboris Lorem irure. Culpa aliquip eu adipisicing in fugiat cupidatat fugiat sit incididunt.",
                            "tag": [
                                {
                                    "value": "Comtrail"
                                },
                                {
                                    "value": "Xiix"
                                },
                                {
                                    "value": "Comveyor"
                                },
                                {
                                    "value": "Lyria"
                                },
                                {
                                    "value": "Bugsall"
                                },
                                {
                                    "value": "Recrisys"
                                }
                            ]
                        },
                        {
                            "id": 3,
                            "name": "Zoarere",
                            "resume": "Minim est amet culpa Lorem eiusmod irure commodo.",
                            "content": "Ea nostrud minim do Lorem excepteur ut et officia ad. Magna culpa eu laborum et adipisicing tempor commodo nulla esse ea culpa. Cillum incididunt adipisicing exercitation laborum eu pariatur enim laborum laborum minim irure Lorem adipisicing. Non Lorem sit amet aliquip dolor non dolor elit exercitation non dolore ad ex exercitation. Amet commodo cillum excepteur consequat deserunt ut non. Ut exercitation adipisicing veniam ullamco et nulla fugiat ex in mollit consectetur anim.",
                            "tag": [
                                {
                                    "value": "Gazak"
                                },
                                {
                                    "value": "Renovize"
                                },
                                {
                                    "value": "Uniworld"
                                },
                                {
                                    "value": "Centree"
                                },
                                {
                                    "value": "Cinesanct"
                                }
                            ]
                        },
                        {
                            "id": 4,
                            "name": "Springbee",
                            "resume": "Excepteur magna consequat sunt exercitation sint eu laborum qui minim amet cupidatat.",
                            "content": "Ea aliqua nisi voluptate cupidatat magna ex cillum consequat adipisicing. Id adipisicing consectetur irure cillum. Velit sunt irure minim labore eu id cillum excepteur reprehenderit adipisicing. Enim et ut aliquip sint eu sit. Consequat nostrud incididunt velit do sunt irure elit officia do aliquip deserunt ut in velit. Adipisicing veniam dolor quis reprehenderit et ex.",
                            "tag": [
                                {
                                    "value": "Isostream"
                                },
                                {
                                    "value": "Digitalus"
                                },
                                {
                                    "value": "Extrawear"
                                },
                                {
                                    "value": "Endipine"
                                },
                                {
                                    "value": "Zanymax"
                                },
                                {
                                    "value": "Quilm"
                                },
                                {
                                    "value": "Corpulse"
                                }
                            ]
                        },
                        {
                            "id": 5,
                            "name": "Songbird",
                            "resume": "Occaecat sit aute minim excepteur in.",
                            "content": "Deserunt velit sint mollit excepteur mollit. Ut ea eiusmod fugiat laborum do nulla non occaecat. Dolore magna et excepteur esse pariatur tempor sint culpa sunt. Laborum consequat consectetur eu Lorem exercitation fugiat ea consequat nisi ut occaecat ipsum pariatur. Commodo amet ex minim nisi proident. Officia ad id non reprehenderit commodo ullamco anim anim.",
                            "tag": [
                                {
                                    "value": "Zaggle"
                                },
                                {
                                    "value": "Architax"
                                },
                                {
                                    "value": "Insurety"
                                },
                                {
                                    "value": "Zillidium"
                                },
                                {
                                    "value": "Kraggle"
                                },
                                {
                                    "value": "Comtrak"
                                }
                            ]
                        }],

		getAll : function(){
			return model.all;
		},

		getShermark : function( id){
			var result = {};
			angular.forEach( model.all, function(value, key){
				if( value.id == id){
					result = value;
				}
			});
			return result;
		}
	};
	return model;
});

//http://localhost:8888/Projets/framework/mode-simple/DEV/article.json