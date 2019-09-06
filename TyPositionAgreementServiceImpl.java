package com.cyjz.personnel.service;

import com.alibaba.dubbo.config.annotation.Service;
import com.alibaba.fastjson.JSONObject;
import com.cyjz.aspect.annotation.EnablePaging;
import com.cyjz.dao.mapper.*;
import com.cyjz.interf.personnel.ITyPositionAgreementService;
import com.cyjz.layui.Layui;
import com.cyjz.pojo.*;
import com.cyjz.util.CommUtil;
import com.cyjz.util.PageUtil;
import com.cyjz.util.RedisUtil;
import com.cyjz.util.tianyou.TianyouUtil;
import com.github.pagehelper.PageHelper;
import org.springframework.amqp.core.AmqpTemplate;
import org.springframework.beans.factory.annotation.Autowired;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

@Service
public class TyPositionAgreementServiceImpl implements ITyPositionAgreementService {

    @Autowired
    private TyPositionAgreementMapper tyPositionAgreementMapper;
    @Autowired
    private TyPositionAgreementRelativeMapper tyPositionAgreementRelativeMapper;
    @Autowired
    private RedisUtil redisUtil;
    @Autowired
    private EmployeeMapper employeeMapper;
    @Autowired
    private DepartmentMapper departmentMapper;

    @Autowired
    private AmqpTemplate amqpTemplate;

    /**
     * 记录日志
     *
     * @param parameters 记录的参数
     * @param msg  消息内容
     */
    private void info(Map<String, Object> parameters, String msg) {
        CommUtil.info(
            departmentMapper,
            amqpTemplate,
            redisUtil,
            employeeMapper,
            parameters,
            msg
        );
    }

    /**
     * 查询协议类型列表
     * @return 类型列表
     */
    public Map<String, Object> selectAgreementExpireTypes() {
        List<Map<String, Object>> types = new ArrayList<Map<String, Object>>();
        
        Map<String, Object> type1 = new HashMap<String, Object>();
        type1.put("typeValue", 1);
        type1.put("typeName", "在职期内");
        Map<String, Object> type2 = new HashMap<String, Object>();
        type2.put("typeValue", 2);
        type2.put("typeName", "签署起3年");
        Map<String, Object> type3 = new HashMap<String, Object>();
        type3.put("typeValue", 3);
        type3.put("typeName", "签署起5年");
        Map<String, Object> type4 = new HashMap<String, Object>();
        type4.put("typeValue", 4);
        type4.put("typeName", "离职2年内");

        return Layui.success().add("data", types);
    }

    @Override
    @EnablePaging
    public Object selectTyPositionAgreementByPage(Map<String, Object> parameters) {
        //分页查询
        PageUtil<Map<String, Object>> page = new PageUtil<Map<String, Object>>(parameters) {
            @Override
            public List<Map<String, Object>> resultList(int currentPage, int pageSize) {
                PageHelper.startPage(currentPage, pageSize);
                List<Map<String, Object>> listProcessAgreement = tyPositionAgreementMapper.selectProcessAgreementList(parameters);
                return listProcessAgreement;
            }
        };
        info(parameters, ":分页查询协议-");
        return page.data("yyyy-MM-dd");
    }

    @Override
    @EnablePaging
    public Object selectTyPositionAgreementRelativeByPage(Map<String, Object> parameters) {
        //分页查询
        PageUtil<Map<String, Object>> page = new PageUtil<Map<String, Object>>(parameters) {
            @Override
            public List<Map<String, Object>> resultList(int currentPage, int pageSize) {
                PageHelper.startPage(currentPage, pageSize);
                List<Map<String, Object>> listProcessAgreementRelative = tyPositionAgreementRelativeMapper.selectProcessAgreementRelativeList(parameters);
                return listProcessAgreementRelative;
            }
        };
        info(parameters, ":分页查询岗位协议关系-");
        return page.data("yyyy-MM-dd");
    }

    @Override
    public Map<String, Object> saveTyPositionAgreement(Map<String, Object> parameters) {
        try {
            TyPositionAgreement record = JSONObject.parseObject(JSONObject.toJSONString(parameters), TyPositionAgreement.class);
            if(record.getId() == null) {
                record.setCreateTime(new Date());
                info(parameters, ":保存协议-协议名称："+record.getName());
                tyPositionAgreementMapper.insert(record);
            }else {
                tyPositionAgreementMapper.updateByPrimaryKeySelective(record);
            }
            return Layui.success();
        }catch (Exception e) {
            e.printStackTrace();
            return Layui.fail();
        }
    }

    @Override
    public Map<String, Object> delTyPositionAgreement(Map<String, Object> parameters) {
        try {
            tyPositionAgreementMapper.deleteByPrimaryKey(CommUtil.null2Long(parameters.get("id")));

            info(parameters,":删除签署协议模版");
            return Layui.success();
        }catch (Exception e) {
            e.printStackTrace();
            return Layui.fail();
        }
    }

    @Override
    public Map<String, Object> selectTyPositionAgreementById(Map<String, Object> parameters) {
        try {
            List<Map<String, Object>> map = tyPositionAgreementMapper.selectAgreementByCondition(parameters);
            info(parameters, ":根据ID查询协议：");
            return Layui.success().add("data", CommUtil.toJSON(map,"yyyy-MM-dd"));
        } catch (Exception e) {
            e.printStackTrace();
            return Layui.fail();
        }
    }

    @Override
    public Map<String, Object> saveTyPositionAgreementRelative(Map<String, Object> parameters) {
        try {
            TyPositionAgreementRelative record = JSONObject.parseObject(
                JSONObject.toJSONString(parameters),
                TyPositionAgreementRelative.class
            );
            if(record.getId() == null) {
                record.setCreateTime(new Date());
                info(parameters, ":保存岗位协议关系");
                tyPositionAgreementRelativeMapper.insert(record);
            }else {
                tyPositionAgreementRelativeMapper.updateByPrimaryKeySelective(record);
            }
            return Layui.success();
        }catch (Exception e) {
            e.printStackTrace();
            return Layui.fail();
        }
    }

    @Override
    public Map<String, Object> delTyPositionAgreementRelative(Map<String, Object> parameters) {
        try {
            tyPositionAgreementRelativeMapper.deleteByPrimaryKey(CommUtil.null2Long(parameters.get("id")));

            info(parameters,":删除岗位协议关系");
            return Layui.success();
        }catch (Exception e) {
            e.printStackTrace();
            return Layui.fail();
        }
    }

    @Override
    public Map<String, Object> selectTyPositionAgreementRelativeById(Map<String, Object> parameters) {
        try {
            List<Map<String, Object>> map = tyPositionAgreementRelativeMapper.selectAgreementRelativeByCondition(parameters);
            info(parameters, ":根据ID查询岗位协议关系：");
            return Layui.success().add("data", CommUtil.toJSON(map,"yyyy-MM-dd"));
        } catch (Exception e) {
            e.printStackTrace();
            return Layui.fail();
        }
    }
}
