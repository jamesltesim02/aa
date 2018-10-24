# poker-cms
### 1. 依赖模块
    pip install requests PyExecJS pillow

### 3. 接口调用
    from pokercms import cmsapi
    # 查询带入提案
    result = cmsapi.getBuyinList(username, password)
    # 接受提案
    result = cmsapi.acceptBuyin(username, password, 691598, 33515925)
    # 拒绝提案
    result = cmsapi.denyBuyin(username, password, 691598, 33515925)
    # 查询俱乐部列表
    result = cmsapi.getClubList(username, password)
    # 查询牌局列表
    result = getHistoryGameList(username, password, 588000, 1538323200000, 1540396800000)
    # 查询战绩
    result = cmsapi.getHistoryGameDetail(username, password, 33484968)
