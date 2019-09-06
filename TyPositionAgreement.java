package com.cyjz.pojo;

import javax.persistence.*;
import java.io.Serializable;
import java.util.Date;

@Table(name = "ty_position_agreement")
public class TyPositionAgreement implements Serializable{

    private static final long serialVersionUID = 1L;

    /**
     * 协议配置表id
     */
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    /**
     * 协议名称
     */
    @Column(name = "name")
    private String name;

    /**
     * 协议路径
     */
    @Column(name = "url")
    private String url;

    /**
     * 备注
     */
    @Column(name = "comment")
    private String comment;

    /**
     * 创建时间
     */
    @Column(name = "create_time")
    private Date createTime;

    /**
     * 创建人
     */
    @Column(name = "create_by")
    private String createBy;

    /**
     * 有效期限类型 
     * 1: 在职期内
     * 2: 签署起3年
     * 3: 签署起5年
     * 4: 离职2年内
     */
    @Column(name = "expire_type")
    private Integer expireType;

    /**
     * 删除标志 
     */
    @Column(name = "delflag")
    private Integer delflag;

    /**
     * 项目id
     */
    @Column(name = "company_id")
    private String companyId;

    public Date getCreateTime() {
        return createTime;
    }

    public void setCreateTime(Date createTime) {
        this.createTime = createTime;
    }

    public String getComment() {
        return comment;
    }

    public void setComment(String comment) {
        this.comment = comment;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Integer getExpireType() {
        return expireType;
    }

    public void setExpireType(Integer expireType) {
        this.expireType = expireType;
    }

    public Integer getDelflag() {
        return delflag;
    }

    public void setDelflag(Integer delflag) {
        this.delflag = delflag;
    }

    public String getCreateBy() {
        return createBy;
    }

    public void setCreateBy(String createBy) {
        this.createBy = createBy;
    }

    public String getCompanyId() {
        return companyId;
    }

    public void setCompanyId(String companyId) {
        this.companyId = companyId;
    }
}
