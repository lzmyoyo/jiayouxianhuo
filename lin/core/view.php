<?php
    class view{
        public static function display($data,$view){
            foreach($data as $key=>$value){
                $$key = $value;
            }
            $viewPath = utils::config('viewPath');
            $viewFile = $viewPath.$view.'.'.utils::config('fileFix');
            if(file_exists($viewFile)){
                unset($data);

                require $viewFile;
            }else{
                throw new myException('文件”'.$viewFile.'“不存在',1);
            }

        }
        public static function includeView($view){
            $viewPath = utils::config('viewPath');
            $viewFile = $viewPath.$view.'.'.utils::config('fileFix');
            require $viewFile;
        }
    }
?>