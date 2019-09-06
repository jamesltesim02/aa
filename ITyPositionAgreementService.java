package com.cyjz.interf.personnel;

import java.util.Map;

/**
 * 协议相关服务
 * 
 * @time 2019-09-06 10:53:04
 * @Author An Hui
 */
public interface ITyPositionAgreementService {
    /**
     * 查询协议类型列表
     * @return 类型列表
     */
    Map<String, Object> selectAgreementExpireTypes();

    /**
     * 保存协议
     */
    Map<String, Object> saveTyPositionAgreement(Map<String, Object> parameters);
    Object selectTyPositionAgreementByPage(Map<String, Object> parameters);
    Object selectTyPositionAgreementRelativeByPage(Map<String, Object> parameters);
    Map<String, Object> delTyPositionAgreement(Map<String, Object> parameters);
    Map<String, Object> selectTyPositionAgreementById(Map<String, Object> parameters);
    Map<String, Object> saveTyPositionAgreementRelative(Map<String, Object> parameters);
    Map<String, Object> delTyPositionAgreementRelative(Map<String, Object> parameters);
    Map<String, Object> selectTyPositionAgreementRelativeById(Map<String, Object> parameters);
}