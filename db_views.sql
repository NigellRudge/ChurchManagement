

drop view if exists member_info;
create view member_info as
    SELECT  members.id as 'id',
            d.name as 'district',
            members.convert_date,
            baptize_date,
            IF(isnull(members.baptize_date),false,true)  as 'baptized',
            Concat (first_name,' ',last_name) as 'name',
            members.birth_date,
            FLOOR(DATEDIFF(curdate(), birth_date) / 365.25) AS 'age',
            g.name as 'gender',
            g.id as 'gender_id',
            phone_number,
            concat(address, ', ', d.name) as 'address',
            id_number,
            member_type.name as 'member_type',
            member_type.id as 'member_type_id',
            email,
            IF(members.active = 1, 'Active', 'In-active') as 'status',
            members.deleted_at,
            members.image,
           members.active as 'active'
from members
         left join member_types member_type on members.member_type_id = member_type.id
         left join genders g on members.gender_id = g.id
         left join districts d on members.district_id = d.id;


drop view if exists eagle_group_info;
create view eagle_group_info as
    SELECT  eagle_groups.id as `id`,
            eagle_groups.name as `name`,
            member_info.name as `team_captain`,
            member_info.id as 'team_captain_id',
            (select count(*) from members mem left join eagle_memberships em on mem.id = em.member_id
                left join eagle_groups eg on em.group_id = eg.id where eg.id = eagle_groups.id) as `num_members`
from eagle_groups
         left join member_info on eagle_groups.team_captain = member_info.id;

drop view if exists converts_info;
create view converts_info as
    SELECT converts.id,
           concat(converts.first_name, ' ', converts.last_name) as name,
           genders.name as gender,
           gender_id,
           genders.trans_string,
           converts.address,
           converts.phone_number,
           converts.convert_date
from converts
         left join genders on converts.gender_id = genders.id;

drop view if exists event_type_info;
create view event_type_info as
    SELECT
           id,
           name,
           code,
           IF(description is null,'No description',description) as 'description',
           IF(active = 1, 'Active', 'In-active') as 'status',
           IF(repeated = 1, 'Yes', 'No')         AS 'repeated',
           IF(period is null, 0, period)         as 'interval',
           created_at,
           updated_at
    FROM event_types;

drop view if exists tide_info;
create view tide_info as
    SELECT
        t.id as 'id',
        m.name AS 'member',
        ##CONCAT(c.code,t.amount) as 'amount',
        t.amount,
        t.member_id,
        c.id as 'currency_id',
        c.code as 'currency_code',
        t.date as 'date',
        (t.amount * c.exchange_rate) as 'amount_in_base_currency'
    FROM tides t LEFT JOIN member_info m on t.member_id = m.id
    LEFT JOIN currencies c on t.currency_id = c.id;


drop view if exists eagle_member_info;
create view eagle_member_info as
    select
        m.id,
        m.name as 'name',
        'Member' as 'position',
        m.phone_number,
        m.email,
        m.gender,
        m.gender_id,
        m.member_type,
        m.image,
        eg.name as 'group',
        eg.id as 'group_id'
from eagle_memberships em inner join  member_info m  on em.member_id = m.id
inner join eagle_groups eg on em.group_id = eg.id;

drop view if exists visitors_info;
create view visitors_info as
    select  visitor.id as id,
            concat(visitor.first_name, ' ', visitor.last_name) as 'name',
            member.name as 'invited_by',
            gender.name as 'gender',
            member.gender_id,
            visitor.date
    from visitors visitor left join  member_info member on visitor.invited_by_id = member.id
        left join genders gender on visitor.gender_id = gender.id;

drop view if exists work_group_member_info;
create view work_group_member_info as
    select  m.id as 'id',
            m.name as 'member',
            m.phone_number,
            m.id as 'member_id',
            m.image,
            m.id_number,
            m.member_type,
            'Member' as 'position',
            wg.name as 'group',
            wg.id as 'work_group_id',
            work_group_memberships.join_date,
            work_group_memberships.exit_date,
            IF(work_group_memberships.active = 1, 'active', 'in-active') as 'status'
    from work_group_memberships
             left join work_groups wg on work_group_memberships.group_id = wg.id
             left join member_info m on work_group_memberships.member_id = m.id;

drop view if exists work_group_info;
create view work_group_info as
    select
          wgroup.id as 'id',
          wgroup.name as 'name',
          pastor.name as 'pastor',
          pastor.id as 'pastor_id',
          coordinator.id as 'coordinator_id',
          coordinator.name as 'coordinator',
          (select count(*) from work_group_memberships where work_group_memberships.group_id = wgroup.id and work_group_memberships.active = 1) as 'active_members'
    from work_groups wgroup left join member_info coordinator on wgroup.coordinator_id = coordinator.id
        left join member_info pastor on wgroup.pastor_id = pastor.id;

drop view if exists pastors_info;
create view pastors_info as
SELECT  id,
        name,
        age,
        phone_number,
        address,
        IFNULL(email,'No Email provided') as 'email',
        IF(active = 1, 'Active', 'In-active') as 'status'
from member_info  where member_type_id = 3;

drop view if exists deacons_info;
create view deacons_info as
SELECT  id,
        name,
        age,
        phone_number,
        address,
        IFNULL(email,'No Email provided') as 'email',
        IF(active = 1, 'Active', 'In-active') as 'status'
from member_info where member_type_id = 2;

drop view if exists elders_info;
create view elders_info as
SELECT  id,
        name,
        age,
        phone_number,
        address,
        IFNULL(email,'No Email provided') as 'email',
        IF(active = 1, 'Active', 'In-active') as 'status'
from member_info where member_type_id = 4;

drop view if exists candidate_members_info;
create view candidate_members_info as
SELECT  id,
        name,
        age,
        phone_number,
        address,
        IFNULL(email,'No Email provided') as 'email',
        IF(active = 1, 'Active', 'In-active') as 'status'
from member_info where member_type_id = 5;

drop view if exists standard_members_info;
create view standard_members_info as
SELECT  id,
        name,
        age,
        phone_number,
        address,
        IFNULL(email,'No Email provided') as 'email',
        IF(active = 1, 'Active', 'In-active') as 'status'
from member_info where member_type_id = 1;

drop view if exists attendance_sheet_info;
create view attendance_sheet_info as
    select sheet.id as 'id',
           sheet.name as 'name',
           sheet.date as 'date',
           (select count(*) from attendance_sheet_item where sheet_id = sheet.id and present=true) as 'members_present'
    from attendance_sheet sheet;


drop view if exists attendance_sheet_rollcall;
create view attendance_sheet_rollcall as
    select member.id as 'id',
           member.name as 'member',
           member.group_id as 'group_id',
           member.group as 'group',
           member.phone_number,
           member.member_type,
           member.gender_id,
           member.image,
           sheet.id as 'sheet_id',
           sheet.name as 'sheet'
    from attendance_sheet_item sheetItem left join eagle_member_info member on sheetItem.member_id = member.id
         left join attendance_sheet sheet on sheetItem.sheet_id = sheet.id;

drop view if exists attendance_sheet_rollcall_not_present;
create view attendance_sheet_rollcall_not_present as
    select member.id as 'id',
           member.name as 'member',
           member.group_id as 'group_id',
           member.group as 'group',
           sheet.id as 'sheet_id',
           sheet.name as 'sheet'
    from  eagle_member_info member
        left join attendance_sheet_item sheetItem on sheetItem.member_id = member.id
        left join attendance_sheet sheet on sheetItem.sheet_id = sheet.id
    where member.id not in (select id from attendance_sheet_rollcall where sheet_id = sheet.id);

drop view if exists church_event_info;
create view church_event_info as
    select event.id as 'id',
           event.title as 'title',
           event.date as 'date',
           event.time as 'time',
           currency.code as 'currency_code',
           IF(event.description is null, 'No description',event.description) as 'description',
           IF(event.should_register = 1,true,false) as 'should_register',
           event.currency_id,
           event.location as 'location',
           IF(event.is_paid_event = 1, 'paid event','free entry') as 'entree_type',
           IF(event.should_register = 1,'yes','no') as 'registration',
           event.last_registration_date,
           event.last_payment_date,
           event.registration_price as 'reg_price',
           IF(event.price = null,null,CONCAT(currency.code,event.price)) as 'price',
           IF(event.registration_price = null,null,CONCAT(currency.code,event.price)) as 'registration_price'
    from church_events event left join currencies currency on event.currency_id = currency.id;

drop view if exists registration_sheet_info;
create view registration_sheet_info as
    select sheet.id as 'id',
           sheet.name,
           sheet.currency_id,
           sheet.last_registration_date,
           sheet.max_registrations,
           sheet.limit_registrations,
           sheet.registration_price as min_amount,
           IF(sheet.registration_price = null,0.00,concat(currency.code,sheet.registration_price)) as 'registration_price',
           IF(sheet.event_id = null,'No event' , event.title) as 'event',
           event_id,
           (select count(*) from registration_sheet_item where sheet_id = sheet.id) as 'registered_members'
    from event_registration_sheet sheet left join church_event_info event on sheet.event_id = event.id
    left join currencies currency on sheet.currency_id = currency.id;


drop view if exists  registration_sheet_item_info;
create view registration_sheet_item_info as
    select item.id as 'id',
           item.member_id,
           item.sheet_id,
           item.registration_date,
           item.paid_amount,
           member.name  as 'member',
           member.phone_number,
           sheet.name as 'sheet',
           currency.code as 'currency_code'
    from registration_sheet_item item left join member_info member on item.member_id = member.id
    left join event_registration_sheet sheet on item.sheet_id = sheet.id
    left join currencies currency on sheet.currency_id = currency.id;

drop view if exists visitors_sheet_info;
create view visitors_sheet_info as
    select sheet.id as 'id',
           sheet.name,
           sheet.date,
           sheet.event_id,
           (select count(*) from visitors_sheet_item where sheet_id = sheet.id) as 'num_visitors'
    from visitors_sheet sheet;

drop view if exists  visitors_sheet_item_info;
create view visitors_sheet_item_info as
    select item.id as 'id',
           item.sheet_id,
           g.name as 'gender',
           item.gender_id,
           concat(first_name, ' ', last_name) as name,
           member.name as 'invited_by',
           sheet.name as 'sheet',
           item.phone_number
    from visitors_sheet_item item left join genders g on item.gender_id = g.id
    left join member_info member on item.invited_by_id = member.id
    left join visitors_sheet sheet on item.sheet_id = sheet.id;

drop view if exists offering_info;
create view offering_info as
    select offerings.id,
           offerings.name,
           offerings.date,
           counted_by,
           srd_amount,
           usd_amount,
           euro_amount,
           event_id,
           IF(event_id != null,church_event_info.title,null) as event,
           (
            (IFNULL(srd_amount,0) * 1) +
            (IFNULL(euro_amount,0) * currency_euro.exchange_rate) +
            (IFNULL(usd_amount,0) * currency_usd.exchange_rate)
            ) as 'total_amount'
    from offerings
        left join church_event_info on offerings.event_id = church_event_info.id
        left join usd currency_usd on currency_usd.id = 2
        left join euro currency_euro on currency_euro.id = 3
    order by date desc;

drop view if exists usd;
create view usd as
    select id,
           name,
           exchange_rate
    from currencies where id = 2;

drop view if exists euro;
create view euro as
    select id,
           name,
           exchange_rate
    from currencies where id = 3;

drop view if exists offerings_report_overview;
create view offerings_report_overview as
    select 'Jan' as date,
           If(sum(info1.total_amount) is null, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-01-01'and'2021-01-31'
    union all
    select 'Feb' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-02-01' and'2021-02-31'
    union all
    select 'Mar' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-03-01' and '2021-03-31'
    union all
    select 'Apr' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-04-01' and '2021-04-31'
    union all
    select 'May' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-05-01' and '2021-05-31'
    union all
    select 'Jun' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-06-01' and '2021-06-31'
    union all
    select 'Jul' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-07-01' and '2021-07-31'
    union all
    select 'Aug' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-08-01' and '2021-08-31'
    union all
    select 'Sep' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-09-01' and '2021-09-31'
    union all
    select 'Oct' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-10-01' and '2021-10-31'
    union all
    select 'Nov' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-11-01' and '2021-11-31'
    union all
    select 'Dec' as date,
           IF(sum(info1.total_amount) IS NULL, 0, sum(info1.total_amount)) as 'amount'
    from offering_info info1 where info1.date between '2021-12-01' and '2021-12-31';


drop view if exists member_relation_overview;
create view member_relation_overview as
    select mr.id as 'id',
           m1.name as 'name',
           m1.id as 'member_id',
           m2.id as 'relative_id',
           m2.name as 'name_relative',
           m2.member_type as 'relative_member_type',
           m2.image as 'relative_image',
           m2.age as 'relative_age',
           rt.name as 'relation',
           rt.trans_code,
           rt.code as 'relation_code'
    from member_relation mr left join  member_info m1 on mr.member_id = m1.id
        left join member_info m2 on mr.related_member_id = m2.id
        left join relationship_types rt on mr.relationship_type_id = rt.id;

drop view if exists seeds_info;
create view seeds_info as
    select
           seed.id as 'id',
           seed.title,
           member.name as 'member',
           member_type,
           image,
           member.gender_id,
           member_id,
           seed.amount,
           seed.date,
           currency_id,
           currency.code as 'currency',
           type.id as 'type_id',
#            type.name as 'type',
           currency.exchange_rate,
           (seed.amount * currency.exchange_rate) as 'amount_in_base_currency'
    from seeds seed left join member_info member on seed.member_id = member.id
    left join currencies currency on seed.currency_id = currency.id
    left join seed_types type on seed.seed_type_id = type.id;


drop view if exists covid_registration_sheet_info;
create view covid_registration_sheet_info as
select sheet.id as 'id',
       sheet.name,
       sheet.date,
       member.name as 'creator',
       sheet.created_by,
       (select count(*) from covid_registration_sheet_item where sheet_id = sheet.id) as 'registered_members'
from covid_registration_sheet sheet left join member_info member on sheet.created_by = member.id;


drop view if exists  covid_registration_sheet_item_info;
create view covid_registration_sheet_item_info as
select item.id as 'id',
       item.member_id,
       item.sheet_id,
       member.name  as 'member',
       member.member_type,
       member.phone_number,
       g.name as 'gender',
       member.id_number,
       sheet.name as 'sheet'
from covid_registration_sheet_item item
     left join member_info member on item.member_id = member.id
     left join covid_registration_sheet sheet on item.sheet_id = sheet.id
     left join genders g on member.gender_id = g.id;

drop view if exists book_item_info;
create view book_item_info as
    select item.id as 'id',
           item.code as 'uid',
           book.title as 'title',
           book_condition.name  as 'condition',
           book_status.name as 'status'
    from book_item as item
    left join books book on item.book_id = book.id
    left join book_condition on item.condition_id = book_condition.id
    left join book_status on item.status_id = book_status.id;


drop view if exists worker_attendance_sheet_info;
create view worker_attendance_sheet_info as
    select wk_sheet.id as 'id',
            wk_sheet.date as 'date',
            wk_sheet.name  as 'name',
            wk_sheet.group_id,
            wg.name as 'group',
            (select count(*) from worker_attendance_sheet_items where sheet_id = wk_sheet.id) as 'members_present'
    from worker_attendance_sheets wk_sheet left join work_groups wg on wk_sheet.group_id = wg.id;


drop view if exists  worker_attendance_item_info;
create view worker_attendance_item_info as
    select s_item.id  as 'id',
           m.name as 'member',
           m.phone_number,
           m.member_type,
           m.id_number,
           s_item.sheet_id,
           s_item.worker_id as 'member_id',
           m.image as 'member_image'
    from worker_attendance_sheet_items s_item left join worker_attendance_sheets sheet on s_item.sheet_id = sheet.id
    left join member_info m on s_item.worker_id = m.id;


drop view if exists infant_dedication_info;
create view infant_dedication_info as
    select  ifd.id as 'id',
            m.name'name',
            m.image,
            m.birth_date,
            m.gender,
            m.gender_id,
            rel1.id as 'mother_id',
            rel1.name as 'mother',
            rel1.image as 'mother_image',
            rel2.id as 'father_id',
            rel2.name as 'father',
            rel2.image as 'father_image',
            ifd.dedication_date
    from infant_dedications ifd left join member_info m on ifd.infant_id = m.id
    left join (select m1.id,m1.name, mrl1.member_id, mrl1.related_member_id,m1.image from member_relation mrl1 left join member_info m1 on mrl1.related_member_id = m1.id where mrl1.relationship_type_id = 3) as rel1 on ifd.infant_id = rel1.member_id
    left join (select m2.id,m2.name, mrl2.member_id, mrl2.related_member_id, m2.image from member_relation mrl2 left join member_info m2 on mrl2.related_member_id = m2.id where  mrl2.relationship_type_id = 4) as rel2 on ifd.infant_id = rel2.member_id;


drop view if exists service_club_member_info;
create view service_club_member_info as
    select sm.id as 'id',
           m.id as 'member_id',
           m.name as 'name',
           m.gender,
           m.age,
           m.gender_id,
           m.image,
           m.member_type,
           sm.business_owner,
           sm.business_name,
           m.id_number,
           sm.created_at as 'join_date',
           sm.profession,
           if(sm.skills is null, 'No skill provided', sm.skills) as 'skills',
           m.phone_number
    from service_club_members sm
        left join member_info m on sm.member_id = m.id;


drop view if exists service_member_sectors_info;
create view service_member_sectors_info as
    select sms.id as 'id',
           scm.member_id as 'member_id',
           scm.id as 'service_id',
           scm.name as 'member_name',
           bs.id as 'sector_id',
           bs.name as 'sector'
    from service_member_sectors  sms left join service_club_member_info scm on sms.service_member_id = scm.id
    left join business_sectors bs on sms.sector_id = bs.id;

drop view if exists member_membership_history;
create view member_membership_history as
    select mms.id,
           mms.membership_type_id,
           mt.name as 'membership_type',
           mms.member_id,
           mms.start_date,
           mms.end_date,
           mms.end_reason
    from member_memberships mms
        left join member_info m  on mms.member_id = m.id
        left join member_types mt on mms.membership_type_id = mt.id;



drop view if exists member_file_info;
create view member_file_info as
    select mf.id as 'id',
           mf.name as 'name',
           mf.file_name,
           mf.member_id,
           u.name as 'uploaded_by',
           mf.created_at as'upload_date'
    from member_files mf
        left join members m on mf.member_id = m.id
        left join users u on mf.uploaded_by = u.id;

drop view if exists sub_accounts_info;
create view sub_accounts_info as
    select sa.id,
           sa.parent_account_id,
           sa.name,
           c.code as 'currency',
           ma.description,
           ma.account_type,
           ma.currency_id,
           sa.deleted_at,
           sa.can_delete,
           ma.name as 'parent_account',
           if( if(isnull(trans_num.count),0,trans_num.count) > 0, false,true) as 'can_edit',
           if(isnull(sa.deleted_at),1,0) as 'active',
           (if(isnull(totals.balance),(0 + 0.00),(totals.balance + 0.00))) as 'balance'
    from sub_accounts sa
        left join main_accounts ma on sa.parent_account_id = ma.id
        left join currencies c on ma.currency_id = c.id
        left join (select sum(amount) as balance,
                          account_id
                    from transactions group by account_id) totals on sa.id = totals.account_id
        left join (select count(*) as 'count', account_id from transactions group by account_id) trans_num on sa.id = trans_num.account_id;


drop view if exists main_account_info;
create view main_account_info as
    select ma.id,
           ma.name,
           ma.description,
           ma.currency_id,
           c.code as 'currency',
           ma.deleted_at,
           ma.account_type,
           if( if(isnull(sub_account_count.count),0,sub_account_count.count) > 0, false,true) as 'can_edit',
           if(isnull(deleted_at),1,0) as 'active',
           (if(isnull(sub_totals.balance),0,sub_totals.balance)) as 'balance'
    from
    main_accounts ma
        left join currencies c on ma.currency_id = c.id
        left join (select sum(balance) as balance,
                          parent_account_id  from sub_accounts_info group by parent_account_id) sub_totals
                on ma.id = sub_totals.parent_account_id
        left join (select count(*) as 'count', parent_account_id from sub_accounts group by parent_account_id) sub_account_count on ma.id = sub_account_count.parent_account_id;

drop view if exists transaction_info;
create view transaction_info as
    select tran.id as 'id',
           seed_id,
           offering_id,
           attachment,
           u.name as 'created_by',
           transaction_date,
           tran.description as 'description',
           account.name as 'account',
           tran.account_id,
           account.parent_account as 'main_account',
           account.currency_id,
           account.currency,
           tran.amount,
           account.account_type as 'tran_type',
           tran.created_at
    from transactions tran
        left join sub_accounts_info account on tran.account_id = account.id
        left join users u on tran.created_by = u.id;


drop view if exists budget_item_info;
create view budget_item_info as
    select item.id,
           item.budget_id,
           item.name,
           item.currency_id,
           (item.amount * currency.exchange_rate) as 'amount_in_base_currency',
           currency.code as 'currency',
           item.amount,
           item.covered_amount,
           item.description,
           item.covered_by_sponsor,
           item.created_by,
           user.name as'creator',
           item.created_at
    from budget_items item
        left join currencies currency on item.currency_id = currency.id
        left join users user on item.created_by = user.id;

drop view if exists budget_info;
create view budget_info as
    select budget.id,
           budget.name,
           budget.description,
           item_totals.amount as 'total_amount',
           budget.covered,
           budget.date,
           budget.created_by,
           user.name as'creator',
            budget.created_at
           from budgets budget
    left join (select budget_id, sum(amount_in_base_currency) as 'amount' from budget_item_info group by budget_id) item_totals
            on budget.id = item_totals.budget_id
    left join users user on budget.created_by = user.id;


drop view if exists user_module_access_info;
create view user_module_access_info as
    select uma.id,
           u.id as 'user_id',
           u.name as 'username',
           m.name as 'module',
           module_id,
           m.code,
           m.module_category
    from user_module_access uma
        left join users u on uma.user_id = u.id
        left join modules m on uma.module_id = m.id;

drop view if exists currency_history_info;
create view currency_history_info as
    select ch.id as 'id',
           c.name as 'currency_name',
           c.code as 'currency_code',
           ch.currency_id,
           ch.start_date,
           ch.end_date,
           ch.rate,
           if(isnull(ch.end_date),true,false) as 'active'
    from currency_histories ch left join currencies c on ch.currency_id = c.id;
