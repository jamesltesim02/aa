package com.cyjz.dao.mapper;

import com.cyjz.dao.base.IBaseMapper;
import com.cyjz.pojo.TyPositionAgreement;

import java.util.List;
import java.util.Map;

public interface TyPositionAgreementMapper extends IBaseMapper<TyPositionAgreement> {
    List<Map<String,Object>> selectProcessAgreementList(Map<String,Object> param);
    List<Map<String,Object>> selectAgreementByCondition(Map<String,Object> param);
}