<?php  namespace Jogento\Keepfpc\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper{

    const PATH_CONFIG_ENABLED = "keep_fpc/general/enable";
    const PATH_CONFIG_WHITELISTED_PARAMETERS = "keep_fpc/general/whitelisted_paramaters";


    /**
     * Check if KeepFPC is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(static::PATH_CONFIG_ENABLED);
    }


    /**
     * Return Whitelisted URL Parameters
     *
     * @return array
     */
    public function getWhitelistedParameters()
    {
        $result = [];
        $parameters = $this->scopeConfig->getValue(static::PATH_CONFIG_WHITELISTED_PARAMETERS);
        if($parameters){
            $result = explode(',',$parameters);
        }
        return $result;
    }

    /**
     * Get URI string without parameters if at least one of the parameters is whitelisted
     * @param $request  \Magento\Framework\App\Request\Http
     *
     * @return string
     */
    public function getUriStringWhitelisted($request)
    {
        $uristring = $request->getUriString();
        $whitelistedParameters = $this->getWhitelistedParameters();
        $requestParams = $request->getParams();
        foreach ($whitelistedParameters as $parameter){
            if(array_key_exists($parameter,$requestParams))
            {
                $uristring = strtok($uristring, '?');
                continue;
            }
        }

        return $uristring;
    }


}