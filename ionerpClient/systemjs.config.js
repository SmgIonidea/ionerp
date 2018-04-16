System.config({
  //use typescript for compilation
  transpiler: 'typescript',
  //typescript compiler options
  typescriptOptions: {
    emitDecoratorMetadata: true
  },
'paths': {
    'npm:':'node_modules/'
},
map: {
      // angular bundles 
      '@angular/core': 'npm:@angular/core/bundles/core.umd.js',
      "ng2-accordion": "node_modules/ng2-accordion",
      // ... 
      // other libraries 
      'rxjs':  'npm:rxjs',
      'angular2-toaster': 'npm:angular2-toaster/bundles/angular2-toaster.umd.js',
	  'mydatepicker': 'npm:mydatepicker/bundles/mydatepicker.umd.min.js'
},
"packages": {
  "ng2-accordion": { "main": "index.js", "defaultExtension": "js" }
}
});