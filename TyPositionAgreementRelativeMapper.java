package com.cyjz.dao.mapper;

import com.cyjz.aspect.annotation.SignPaging;
import com.cyjz.dao.base.IBaseMapper;
import com.cyjz.pojo.TyPositionAgreement;
import com.cyjz.pojo.TyPositionAgreementRelative;
import org.apache.ibatis.annotations.Select;

import java.util.List;
import java.util.Map;

public interface TyPositionAgreementRelativeMapper extends IBaseMapper<TyPositionAgreementRelative> {
    List<Map<String,Object>> selectProcessAgreementRelativeList(Map<String,Object> param);
    List<Map<String,Object>> selectAgreementRelativeByCondition(Map<String,Object> param);
}