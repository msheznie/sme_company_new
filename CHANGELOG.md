# Changelog

## [1.8.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.7.0...v1.8.0) (2025-11-11)


### Features

* **cm:** CMS | Mitigate XSS via malicious PDF upload by enforcing strict file validation, sanitization, and safe file serving [CM-924] ([#383](https://github.com/pbsgears/Contract_Management_Backend/issues/383)) ([eb730f6](https://github.com/pbsgears/Contract_Management_Backend/commit/eb730f601ac829b320c0a2f12add607902938e4c))
* **cm:** Remove unnecessary parameters from API requests and responses [CM-879] ([#380](https://github.com/pbsgears/Contract_Management_Backend/issues/380)) ([3897085](https://github.com/pbsgears/Contract_Management_Backend/commit/38970859141c0c05d866862be01121df4566b33a))
* **contract:** CSRF Implementation [CM-878] ([#379](https://github.com/pbsgears/Contract_Management_Backend/issues/379)) ([f29cf99](https://github.com/pbsgears/Contract_Management_Backend/commit/f29cf993d78b50463d8dc62e2fced571c802617a))

## [1.7.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.6.0...v1.7.0) (2025-09-29)


### Features

* **CM:** Show Supplier in all the subsidiaries Supplier Master [CM-768] ([#357](https://github.com/pbsgears/Contract_Management_Backend/issues/357)) ([62314b4](https://github.com/pbsgears/Contract_Management_Backend/commit/62314b41392836cd48647e586b46918b8ebeba8c))
* **contract:** Giving Contract Access to other subsidiary employees [CM-745] ([ec55bed](https://github.com/pbsgears/Contract_Management_Backend/commit/ec55bed104c570a8818be58f45b0c9a0c68eb8e2))
* **contract:** initializing language swith in backend [CM-791] ([#367](https://github.com/pbsgears/Contract_Management_Backend/issues/367)) ([b89f9d9](https://github.com/pbsgears/Contract_Management_Backend/commit/b89f9d97da523c40310e93615408e374143455a0))


### Bug Fixes

* **CMS:** users not loading to the contract users [CM-767] ([#356](https://github.com/pbsgears/Contract_Management_Backend/issues/356)) ([de1a94d](https://github.com/pbsgears/Contract_Management_Backend/commit/de1a94dc1d422d26fde99d0f0fb0b1871744d03a))
* **contracts:** Inactive users are displaying for the Counterparty Name dropdown [CM-732] ([#350](https://github.com/pbsgears/Contract_Management_Backend/issues/350)) ([b876424](https://github.com/pbsgears/Contract_Management_Backend/commit/b876424a5446a7970d7900f464b3f36f249a4a63))


### Miscellaneous Chores

* release 1.6.0 ([de293ec](https://github.com/pbsgears/Contract_Management_Backend/commit/de293ecb9bb4f5c59137f279bde41f0dccb39587))
* release 1.6.0 ([28f100f](https://github.com/pbsgears/Contract_Management_Backend/commit/28f100f58a38745c820e17d0f50ac1247881e4ae))

## [1.6.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.5.0...v1.6.0) (2024-12-09)


### Miscellaneous Chores

* release 1.5.0 ([4d63be2](https://github.com/pbsgears/Contract_Management_Backend/commit/4d63be20b08e2364603d64ba1d2b333c98870ff2))

## [1.5.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.4.0...v1.5.0) (2024-12-02)


### Features

* **CM:** Any user can access and extract sensitive information through the linked API by browsing the URL [CM-680] ([#325](https://github.com/pbsgears/Contract_Management_Backend/issues/325)) ([f373d35](https://github.com/pbsgears/Contract_Management_Backend/commit/f373d358a88cca4a3732d7e23931265c38009edf))
* **CM:** Contract and Milestone Detail Report Enhancement [CM-387] ([#317](https://github.com/pbsgears/Contract_Management_Backend/issues/317)) ([11cf155](https://github.com/pbsgears/Contract_Management_Backend/commit/11cf155c9f6ca4a66213d99419927df0a7b33eba))
* **CM:** emp verivied checking [CM-557] ([#318](https://github.com/pbsgears/Contract_Management_Backend/issues/318)) ([0cebf2a](https://github.com/pbsgears/Contract_Management_Backend/commit/0cebf2a14855e2bc62c2edd3825f908efa80eb8f))
* **CM:** Penalty Calculation Frequency [CM-627] ([#319](https://github.com/pbsgears/Contract_Management_Backend/issues/319)) ([ba0fe3f](https://github.com/pbsgears/Contract_Management_Backend/commit/ba0fe3f8793ab7a40143df1729b94001754c9e3a))
* **cms:** remove sesitive information login [CM-643] ([#314](https://github.com/pbsgears/Contract_Management_Backend/issues/314)) ([60cc015](https://github.com/pbsgears/Contract_Management_Backend/commit/60cc0154787bf302aee9a908bfcfa5fed67aa226))
* **contract:** contract view page filter [CM-408] ([#316](https://github.com/pbsgears/Contract_Management_Backend/issues/316)) ([a516ab9](https://github.com/pbsgears/Contract_Management_Backend/commit/a516ab9cbe00213f21d572feb38468197f4ef9c6))
* **contract:** document code configuration [CM-637] ([#324](https://github.com/pbsgears/Contract_Management_Backend/issues/324)) ([79d178a](https://github.com/pbsgears/Contract_Management_Backend/commit/79d178a7f084ea91ffcbfa8337198837556183c5))
* **contracts:** Contract | Amendment | Attachment Option [CM-610] ([#320](https://github.com/pbsgears/Contract_Management_Backend/issues/320)) ([b2f6162](https://github.com/pbsgears/Contract_Management_Backend/commit/b2f616220394bae8ba4942c0e650485b4bb5aa23))
* **contracts:** Description Change from "Contract ID" to "Contract Code" [CM-507] ([#315](https://github.com/pbsgears/Contract_Management_Backend/issues/315)) ([779bd0d](https://github.com/pbsgears/Contract_Management_Backend/commit/779bd0d8dcd4360cf4cbb1756db78a9e901f4401))
* **srm:** All the forms are vulnerable to CSRF [CM-642] ([#308](https://github.com/pbsgears/Contract_Management_Backend/issues/308)) ([a8e553c](https://github.com/pbsgears/Contract_Management_Backend/commit/a8e553c96b067f2abc293b87944ce1b72bfd2827))


### Bug Fixes

* **CM:** EMP VERIFY control issue altered [CM-557] ([#321](https://github.com/pbsgears/Contract_Management_Backend/issues/321)) ([bcae64d](https://github.com/pbsgears/Contract_Management_Backend/commit/bcae64d57a3c7da3222abad2c9a0f0d1435150aa))
* **CM:** EMP VERIFY control issue altered 3 [CM-557] ([#323](https://github.com/pbsgears/Contract_Management_Backend/issues/323)) ([8bc18c3](https://github.com/pbsgears/Contract_Management_Backend/commit/8bc18c35f9011ba64212a75263cf3f57ce71140c))
* **contract:** amount not displaying in payment schedule [CM-699] ([#329](https://github.com/pbsgears/Contract_Management_Backend/issues/329)) ([df0f27e](https://github.com/pbsgears/Contract_Management_Backend/commit/df0f27e6d38e83a6c3207cebf5c05f8f320bce21))
* **contract:** In contract amendment, display error message when pulling BOQ items [CM-720] ([#338](https://github.com/pbsgears/Contract_Management_Backend/issues/338)) ([6e03481](https://github.com/pbsgears/Contract_Management_Backend/commit/6e03481587b75de0cca97334f9e7aa43ca3a2034))
* **contracts:** 'Contract Amount' field is not auto populated in the 'Overall Retention' [CM-702] ([#330](https://github.com/pbsgears/Contract_Management_Backend/issues/330)) ([6d81a09](https://github.com/pbsgears/Contract_Management_Backend/commit/6d81a0998b88e68e4c84439cbe7ecf7111ff0105))
* **contracts:** Cannot pull BOQ items from master table and pulled items from tender not display [CM-697] ([#328](https://github.com/pbsgears/Contract_Management_Backend/issues/328)) ([c44ad73](https://github.com/pbsgears/Contract_Management_Backend/commit/c44ad73d4318660226f3eb32d94a47138074844a))
* **contracts:** Display invalid item code for BOQ items in the 'Amendment Contract History' popup [CM-724] ([#340](https://github.com/pbsgears/Contract_Management_Backend/issues/340)) ([8e0c3d5](https://github.com/pbsgears/Contract_Management_Backend/commit/8e0c3d5ea4a2d6c30a3a9a68d0db16e601462baf))
* **contracts:** Display sensitive information when retrieving data for the 'Dashboard' [CM-696] ([#327](https://github.com/pbsgears/Contract_Management_Backend/issues/327)) ([f31507a](https://github.com/pbsgears/Contract_Management_Backend/commit/f31507a637a4463c765f06a076d1f9cfe3ed78d5))
* **contracts:** Duplicate the assigned default user group when adding a new user to that default user group [CM-173] ([#322](https://github.com/pbsgears/Contract_Management_Backend/issues/322)) ([4bf91ad](https://github.com/pbsgears/Contract_Management_Backend/commit/4bf91adb16287e094eb1a117d59066934b468721))
* **contracts:** User cannot export the excel file in the 'Contract and Milestone Details' page [CM-712] ([#336](https://github.com/pbsgears/Contract_Management_Backend/issues/336)) ([2832d2c](https://github.com/pbsgears/Contract_Management_Backend/commit/2832d2c029d05caa02fa2951f678d47f52e26469))
* **contracts:** User not display added details for a milestone penalty record when editing the record [CM-709] ([#335](https://github.com/pbsgears/Contract_Management_Backend/issues/335)) ([b47006c](https://github.com/pbsgears/Contract_Management_Backend/commit/b47006c789c7bfe5cc685e085d736c3cdc92c8bc))
* **contract:** User cannot create an addendum from parent contract [CM-713] ([#337](https://github.com/pbsgears/Contract_Management_Backend/issues/337)) ([32f4e28](https://github.com/pbsgears/Contract_Management_Backend/commit/32f4e28837dad191923d43f9d1ae0eb1cc00c593))

## [1.4.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.3.0...v1.4.0) (2024-10-25)


### Features

* **contract:** amendment | contract payment terms [CM-405] ([#287](https://github.com/pbsgears/Contract_Management_Backend/issues/287)) ([c8e6764](https://github.com/pbsgears/Contract_Management_Backend/commit/c8e6764fb405dae7219a028c1f3f54106c477259))
* **contract:** contract amendment | BOQ [CM-406] ([#295](https://github.com/pbsgears/Contract_Management_Backend/issues/295)) ([ab4e1b2](https://github.com/pbsgears/Contract_Management_Backend/commit/ab4e1b2e14a98e5a33931c5c275f45a397c5e43d))
* **contract:** Contract Amendment | Retention [CM-412] ([#289](https://github.com/pbsgears/Contract_Management_Backend/issues/289)) ([c6daac3](https://github.com/pbsgears/Contract_Management_Backend/commit/c6daac3a092bcc318d1a3b20d60d784dc6f0ffb2))
* **contract:** Enhancement | Due Penalty Amount field | Overall & Milestone Penalty [CM-394] ([#285](https://github.com/pbsgears/Contract_Management_Backend/issues/285)) ([003349f](https://github.com/pbsgears/Contract_Management_Backend/commit/003349f4e98655ebf7409aa0a648d804795f3065))
* **contracts:** Contract Approval Enhancement [CM-494] ([#291](https://github.com/pbsgears/Contract_Management_Backend/issues/291)) ([9d9ae74](https://github.com/pbsgears/Contract_Management_Backend/commit/9d9ae7459a4a2d43dc4c19841d0d5a4dda5d3593))
* **contracts:** Contract Referback Option [CM-458] ([#286](https://github.com/pbsgears/Contract_Management_Backend/issues/286)) ([5feb770](https://github.com/pbsgears/Contract_Management_Backend/commit/5feb7703318365f64ddd1c66798ad69b41b4c0cb))
* **contract:** show contract amendment history in amendment approved tab [CM-496] ([#302](https://github.com/pbsgears/Contract_Management_Backend/issues/302)) ([13cd561](https://github.com/pbsgears/Contract_Management_Backend/commit/13cd5619bd8366be245d568fdf4c8aab4a46a2e6))
* **contracts:** Reminder Configuration for Document Expiry [CM-437] ([#298](https://github.com/pbsgears/Contract_Management_Backend/issues/298)) ([2805569](https://github.com/pbsgears/Contract_Management_Backend/commit/28055691644cd72c35e0a56cb48192350a6b62d4))
* **contracts:** Reminder Configuration for Milestone Completion [CM-438] ([#293](https://github.com/pbsgears/Contract_Management_Backend/issues/293)) ([4309fdc](https://github.com/pbsgears/Contract_Management_Backend/commit/4309fdc38bcca2ce7a988a558ab149758acf6a82))


### Bug Fixes

* **contract:** Retention End Date Validation [CM-639] ([#306](https://github.com/pbsgears/Contract_Management_Backend/issues/306)) ([574516f](https://github.com/pbsgears/Contract_Management_Backend/commit/574516fef84e3273448339949c894fb29315ae3e))
* **contracts:** If there is already data added for 'Milestone and Payment Schedules', delete the existing data before changing to another option [CM-391] ([#284](https://github.com/pbsgears/Contract_Management_Backend/issues/284)) ([43741b7](https://github.com/pbsgears/Contract_Management_Backend/commit/43741b7f9d538ca30103db2aca801de589d6af80))
* **contracts:** When creating a milestone penalty, user cannot get milestone amount when selecting a milestone title [CM-618] ([#303](https://github.com/pbsgears/Contract_Management_Backend/issues/303)) ([8651293](https://github.com/pbsgears/Contract_Management_Backend/commit/86512935c7a274c61eb724309bbf4ef54ce53acf))

## [1.3.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.2.0...v1.3.0) (2024-10-09)


### Features

* **contract:** contract effective date settings [CM-456] ([#273](https://github.com/pbsgears/Contract_Management_Backend/issues/273)) ([1e37ffc](https://github.com/pbsgears/Contract_Management_Backend/commit/1e37ffc89af9992e2723b86ff3e785c370c53531))
* **contract:** Enhancement | attach document [CM-307] ([#262](https://github.com/pbsgears/Contract_Management_Backend/issues/262)) ([5c9cce1](https://github.com/pbsgears/Contract_Management_Backend/commit/5c9cce134d405ffcf00f8908e7319a563c4271b7))
* **contract:** finance document enhancement [CM-410] ([#271](https://github.com/pbsgears/Contract_Management_Backend/issues/271)) ([395dddf](https://github.com/pbsgears/Contract_Management_Backend/commit/395dddf55e142888436516c09e131102dd5248b6))
* **contract:** milestone & payment schedule finance integration enhancement [CM-415] ([#267](https://github.com/pbsgears/Contract_Management_Backend/issues/267)) ([70567e4](https://github.com/pbsgears/Contract_Management_Backend/commit/70567e4463e802b0b0269d185f0f825baeda869f))
* **contract:** Milestone Deliverable Status [CM-420] ([#264](https://github.com/pbsgears/Contract_Management_Backend/issues/264)) ([f0bbfec](https://github.com/pbsgears/Contract_Management_Backend/commit/f0bbfececd88fb6ccc39373afa03518ca9e09999))
* **contracts:** Capturing Milestone Due Date [CM-413] ([#261](https://github.com/pbsgears/Contract_Management_Backend/issues/261)) ([aab9a5d](https://github.com/pbsgears/Contract_Management_Backend/commit/aab9a5d6bd1e55a2a9ab269bd0b19544506da24f))
* **contracts:** Contract Termination Chrone Job [CM-510] ([#275](https://github.com/pbsgears/Contract_Management_Backend/issues/275)) ([072d7b2](https://github.com/pbsgears/Contract_Management_Backend/commit/072d7b2498fbfe49e6b5cc7472d17c8870bd5b33))
* **contracts:** EEnhancement | Milestone Status [CM-419] ([#257](https://github.com/pbsgears/Contract_Management_Backend/issues/257)) ([6cd97b1](https://github.com/pbsgears/Contract_Management_Backend/commit/6cd97b1081971073a4c9068808854b727389d62b))
* **contracts:** Enhancement | Contract Info | Supplier Details [CM-418] ([#259](https://github.com/pbsgears/Contract_Management_Backend/issues/259)) ([4b9f06e](https://github.com/pbsgears/Contract_Management_Backend/commit/4b9f06ec18e5e5a9d6aec9ba646495d579197212))
* **contracts:** Enhancement | Contract Users | Department [CM-416] ([#256](https://github.com/pbsgears/Contract_Management_Backend/issues/256)) ([b915736](https://github.com/pbsgears/Contract_Management_Backend/commit/b915736ab74a33623fa6405535879fa060e5064b))
* **contracts:** Enhancement | Users & User Group | Department [CM-417] ([#258](https://github.com/pbsgears/Contract_Management_Backend/issues/258)) ([43fab40](https://github.com/pbsgears/Contract_Management_Backend/commit/43fab40555a792809ea1e7c4a166c975698bf3c8))
* **contracts:** Enhancement to the Contract History Workflow - Amendment [CM-444] ([#270](https://github.com/pbsgears/Contract_Management_Backend/issues/270)) ([790d35d](https://github.com/pbsgears/Contract_Management_Backend/commit/790d35d8d1d369732494bfe1a1626b0c9f50eeee))
* **contracts:** Enhancement to the Contract History Workflow - Extension [CM-447] ([#272](https://github.com/pbsgears/Contract_Management_Backend/issues/272)) ([1ce1781](https://github.com/pbsgears/Contract_Management_Backend/commit/1ce1781e77dad865f4210224cd0c777a74cac858))
* **contracts:** Enhancement to the Contract History Workflow - Renew… ([#268](https://github.com/pbsgears/Contract_Management_Backend/issues/268)) ([954b4da](https://github.com/pbsgears/Contract_Management_Backend/commit/954b4da4924b78916f707452dfe93fcd7ac2b1a0))
* **contracts:** Enhancement to the Contract History Workflow - Termi… ([#269](https://github.com/pbsgears/Contract_Management_Backend/issues/269)) ([829949b](https://github.com/pbsgears/Contract_Management_Backend/commit/829949b377f922fe2d85140e876287b2bc316730))
* **contracts:** Milestone Due Date Validation - RC [CM-471] ([#265](https://github.com/pbsgears/Contract_Management_Backend/issues/265)) ([645908f](https://github.com/pbsgears/Contract_Management_Backend/commit/645908fef229c8946cb1355abe4514e284cbfd54))
* **contract:** SSO issue and remove the logging page when logging via the portal [CM-515] ([#274](https://github.com/pbsgears/Contract_Management_Backend/issues/274)) ([3f99676](https://github.com/pbsgears/Contract_Management_Backend/commit/3f996766224848679e825da4e141533005146876))

## [1.2.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.1.0...v1.2.0) (2024-08-20)


### Features

* **contract:** finance document integration file creation [CM-325] ([#232](https://github.com/pbsgears/Contract_Management_Backend/issues/232)) ([2985ee9](https://github.com/pbsgears/Contract_Management_Backend/commit/2985ee947d4becce9f9556947c1cd3dab44e469d))
* **contract:** milestone and payment schedule finance document print [CM-326] ([#246](https://github.com/pbsgears/Contract_Management_Backend/issues/246)) ([11e43a7](https://github.com/pbsgears/Contract_Management_Backend/commit/11e43a7b1e23e085fa2e54076e38cdd7e056bcdb))
* **contracts:** Finance Summary Tab [CM-332] ([#240](https://github.com/pbsgears/Contract_Management_Backend/issues/240)) ([425878f](https://github.com/pbsgears/Contract_Management_Backend/commit/425878feeb6ee1a5628263a80b7be46186d55319))


### Bug Fixes

* **contracts:** Document Status not available for 'Rejected' Invoices and Payment Vouchers in 'Retention' page [CM-398] ([#247](https://github.com/pbsgears/Contract_Management_Backend/issues/247)) ([522eba2](https://github.com/pbsgears/Contract_Management_Backend/commit/522eba2aeaa08e12b3c5d36e1f2c57df784c5421))

## [1.1.0](https://github.com/pbsgears/Contract_Management_Backend/compare/v1.0.0...v1.1.0) (2024-08-12)


### Features

* **Contract History Amendment:** Enhancement | Contract | Amendment [CM-281] ([#207](https://github.com/pbsgears/Contract_Management_Backend/issues/207)) ([033bacc](https://github.com/pbsgears/Contract_Management_Backend/commit/033baccf5e1537204b6b8c9df24c21f3ca4b686b))
* **Contract History Status:** Contract History | Status History. [CM-34] ([#204](https://github.com/pbsgears/Contract_Management_Backend/issues/204)) ([8bc8851](https://github.com/pbsgears/Contract_Management_Backend/commit/8bc8851d91a0940724bd1444a8cbdd8e65f137ed))
* **Contract info:** Remove the Primary and Secondary email duplication validation in 'Contract Info' page [CM-316] ([#205](https://github.com/pbsgears/Contract_Management_Backend/issues/205)) ([cde19ca](https://github.com/pbsgears/Contract_Management_Backend/commit/cde19cac23b47d79d06ae3eae2c8c1d30f06c6f6))
* **Contract:** API Development to show the contract ID / Reference in Finance & Proc [CM-284] ([#196](https://github.com/pbsgears/Contract_Management_Backend/issues/196)) ([6d37f83](https://github.com/pbsgears/Contract_Management_Backend/commit/6d37f8374c8e5c394c1d407fefa6daeb414e776a))
* **contract:** contract reports [CM-292] ([#208](https://github.com/pbsgears/Contract_Management_Backend/issues/208)) ([2aeda97](https://github.com/pbsgears/Contract_Management_Backend/commit/2aeda97b84dc5badba43b2df98dc69fa3b8ab4fa))
* **Contract:** Enhancement | Contract | Attach Document [CM-159] ([#194](https://github.com/pbsgears/Contract_Management_Backend/issues/194)) ([d92323d](https://github.com/pbsgears/Contract_Management_Backend/commit/d92323d03743278d3bbf699f14eaad01892f1dde))
* **Contract:** Enhancement |Contract History | Validation | Action | Renewal, Addendum, Revision, Termination [CM-291] ([#200](https://github.com/pbsgears/Contract_Management_Backend/issues/200)) ([63f12a4](https://github.com/pbsgears/Contract_Management_Backend/commit/63f12a406fa6ee394e2dda3c848b086446afb339))
* **contract:** Enhancement contract status ended [CM-285] ([#211](https://github.com/pbsgears/Contract_Management_Backend/issues/211)) ([f61b8cd](https://github.com/pbsgears/Contract_Management_Backend/commit/f61b8cd0bdfbe6efe09dd7046459b804301e3d3e))
* **contracts:** Contract | Create Penalty | Milestone Penalty [CM-18] ([#218](https://github.com/pbsgears/Contract_Management_Backend/issues/218)) ([fc51060](https://github.com/pbsgears/Contract_Management_Backend/commit/fc510601491be39fef2336a21c1695df5c449996))
* **contracts:** Contract | Create Penalty | Overall Penalty [CM-17] ([#198](https://github.com/pbsgears/Contract_Management_Backend/issues/198)) ([03e0547](https://github.com/pbsgears/Contract_Management_Backend/commit/03e05472d22c5f14d637669c626e38482ffbc437))
* **contracts:** Enhancement | Contract Info | remove Notify Days [CM-287] ([#190](https://github.com/pbsgears/Contract_Management_Backend/issues/190)) ([9925308](https://github.com/pbsgears/Contract_Management_Backend/commit/9925308ae6f4b19d35d0152d466670e6a6bd44b0))


### Bug Fixes

* **Contract Master:** Add serial numbers to contracts [CM-365] ([#212](https://github.com/pbsgears/Contract_Management_Backend/issues/212)) ([f23a1b3](https://github.com/pbsgears/Contract_Management_Backend/commit/f23a1b38150c116498400439d4cda9a9a163b80f))
* **contract:** contract status does not get activated once the contract start date is reached [CM-379] ([#230](https://github.com/pbsgears/Contract_Management_Backend/issues/230)) ([28688cb](https://github.com/pbsgears/Contract_Management_Backend/commit/28688cb28dfed9545127b4a566ef28f57dd126ee))
* **contracts:** Due Penalty Amount field is null in the excel file when the Due Penalty Amount is displays as 0 in front-end [CM-378] ([#229](https://github.com/pbsgears/Contract_Management_Backend/issues/229)) ([a7b8dba](https://github.com/pbsgears/Contract_Management_Backend/commit/a7b8dba25af36a4f69adf608f59cb9f6fd3330ce))
* **contracts:** User can add multiple milestone penalties with same milestone [CM-375] ([#222](https://github.com/pbsgears/Contract_Management_Backend/issues/222)) ([5191d89](https://github.com/pbsgears/Contract_Management_Backend/commit/5191d895675881773cf90b7c9177477bf174b436))

## 1.0.0 (2024-07-24)


### Features

* **Admin Settings:** uuid implemented for user type crud [CM-2] ([#16](https://github.com/pbsgears/Contract_Management_Backend/issues/16)) ([8cb1d7f](https://github.com/pbsgears/Contract_Management_Backend/commit/8cb1d7f392ba2ac2d84fe408847c5e17c940b6b5))
* **Admin Settings:** uuid implemented for user type crud [CM-2] ([#16](https://github.com/pbsgears/Contract_Management_Backend/issues/16)) ([c64c598](https://github.com/pbsgears/Contract_Management_Backend/commit/c64c5985bc2910663c12f0317f752aa433dad3e6))
* **CM login:** checking multi tenancy when login [CM-16] ([3732a48](https://github.com/pbsgears/Contract_Management_Backend/commit/3732a48531cdcf2531c037b164880761cc342c34))
* **CM Login:** CMS Single sign on implemented from portal [CM-16] ([88dfad0](https://github.com/pbsgears/Contract_Management_Backend/commit/88dfad07e14b99462cbcb716c68adecb1f7a2e45))
* **CM:** implemented login page for contract management [CM-16] ([f241ee7](https://github.com/pbsgears/Contract_Management_Backend/commit/f241ee7a6c09a7901933a46c3a9a9f26783d2023))
* **Contract History:** Contract | Contract Status Flow [CM-170] ([#124](https://github.com/pbsgears/Contract_Management_Backend/issues/124)) ([9080388](https://github.com/pbsgears/Contract_Management_Backend/commit/90803887ff7f37419697986eb7de9083fa3b202c))
* **contract management:** hide primary keys and foreign keys in datatable [CM-129] ([#55](https://github.com/pbsgears/Contract_Management_Backend/issues/55)) ([ee47143](https://github.com/pbsgears/Contract_Management_Backend/commit/ee471432747e9a1574be0edf5b6e6106ba82c093))
* **contract management:** hide primary keys and foreign keys in datatable [CM-129] ([#55](https://github.com/pbsgears/Contract_Management_Backend/issues/55)) ([f8c343b](https://github.com/pbsgears/Contract_Management_Backend/commit/f8c343bb067b2645afbf0ff8841c80958958235c))
* **contract master:** contract milestone and deliverables crud [CM-12] ([#30](https://github.com/pbsgears/Contract_Management_Backend/issues/30)) ([c7eebb7](https://github.com/pbsgears/Contract_Management_Backend/commit/c7eebb7aa061b7bd997ff7831eb7f923a710f8a8))
* **contract master:** contract milestone and deliverables crud [CM-12] ([#30](https://github.com/pbsgears/Contract_Management_Backend/issues/30)) ([1ff6aef](https://github.com/pbsgears/Contract_Management_Backend/commit/1ff6aefceb409498d4fd2250d00513a8d2e1de5f))
* **Contract Users:** Pull contract users internal, supplier and customers [CM-3] ([#15](https://github.com/pbsgears/Contract_Management_Backend/issues/15)) ([485522b](https://github.com/pbsgears/Contract_Management_Backend/commit/485522b577753d4065b684b17eda912ed839644f))
* **Contract Users:** Pull contract users internal, supplier and customers [CM-3] ([#15](https://github.com/pbsgears/Contract_Management_Backend/issues/15)) ([0855af0](https://github.com/pbsgears/Contract_Management_Backend/commit/0855af039bb31bc453acd67bca9583a9ac95a033))
* **contract:** Approval status for contract [CM-97] ([#93](https://github.com/pbsgears/Contract_Management_Backend/issues/93)) ([db36adc](https://github.com/pbsgears/Contract_Management_Backend/commit/db36adcb91ac2071e1b6378f6082136e1e2c91f2))
* **contract:** contract amendment approvals [CM-247] ([#142](https://github.com/pbsgears/Contract_Management_Backend/issues/142)) ([31186b0](https://github.com/pbsgears/Contract_Management_Backend/commit/31186b04472b01c58671f1f2b7255e136a8b1ece))
* **contract:** contract approval [CM-138] ([#129](https://github.com/pbsgears/Contract_Management_Backend/issues/129)) ([a0ad03b](https://github.com/pbsgears/Contract_Management_Backend/commit/a0ad03b019bfbc13e8a5d2d8b19141287a408bb2))
* **contract:** contract approval [CM-96] ([#68](https://github.com/pbsgears/Contract_Management_Backend/issues/68)) ([70a71ee](https://github.com/pbsgears/Contract_Management_Backend/commit/70a71ee7c9156f05575ecffa9ad6c9608510a291))
* **contract:** contrat management approval setup [CM-91] ([#57](https://github.com/pbsgears/Contract_Management_Backend/issues/57)) ([18bae5e](https://github.com/pbsgears/Contract_Management_Backend/commit/18bae5ecf8685dc3132a71453f9ee0e9c27d0aa1))
* **contract:** Create Milestone and Payment Schedule [CM-13] ([#123](https://github.com/pbsgears/Contract_Management_Backend/issues/123)) ([e3c3286](https://github.com/pbsgears/Contract_Management_Backend/commit/e3c32867ed14178668b571a5f3c8b96e03582cfd))
* **contract:** document master backend file creation [CM-89] ([#36](https://github.com/pbsgears/Contract_Management_Backend/issues/36)) ([ef17e5a](https://github.com/pbsgears/Contract_Management_Backend/commit/ef17e5aeda56da2ad823b55c9fddbd4e41ed6f94))
* **contract:** document master backend file creation [CM-89] ([#36](https://github.com/pbsgears/Contract_Management_Backend/issues/36)) ([9a539a5](https://github.com/pbsgears/Contract_Management_Backend/commit/9a539a50f8be6515a81baa3b7b39f12576429c92))
* **contract:** Enhancement Contract History [CM-206] ([#132](https://github.com/pbsgears/Contract_Management_Backend/issues/132)) ([c928ae7](https://github.com/pbsgears/Contract_Management_Backend/commit/c928ae7cb1691f5f6d2b5dced1038ba12274d3f7))
* **contracts:** At least one default user group has to be available in the 'Contract User Groups' [CM-246] ([#137](https://github.com/pbsgears/Contract_Management_Backend/issues/137)) ([967d793](https://github.com/pbsgears/Contract_Management_Backend/commit/967d793150584e88a95918a31b3e16418fea276d))
* **contracts:** Change | Contract | Contract Confirmation [CM-234] ([#139](https://github.com/pbsgears/Contract_Management_Backend/issues/139)) ([3a48ccf](https://github.com/pbsgears/Contract_Management_Backend/commit/3a48ccfe380a2c385a87f9b4e9bbc563cd50f5be))
* **contracts:** Contract | Contract History | Extension [CM-29] ([#76](https://github.com/pbsgears/Contract_Management_Backend/issues/76)) ([31d4ded](https://github.com/pbsgears/Contract_Management_Backend/commit/31d4dedcdb5109fac60ff05237bbb9f8b85c5b09))
* **contracts:** Contract | Create Payment Terms [CM-19] ([#128](https://github.com/pbsgears/Contract_Management_Backend/issues/128)) ([bbe3d5e](https://github.com/pbsgears/Contract_Management_Backend/commit/bbe3d5eb777d8fe0a31ccb6b239b595e64f43fca))
* **contracts:** Contract | Create Retention | Milestone Retention[CM… ([#35](https://github.com/pbsgears/Contract_Management_Backend/issues/35)) ([f900485](https://github.com/pbsgears/Contract_Management_Backend/commit/f90048536facb222efbb2f6af1724c715b05543c))
* **contracts:** Contract | Create Retention | Milestone Retention[CM… ([#35](https://github.com/pbsgears/Contract_Management_Backend/issues/35)) ([fb0e70b](https://github.com/pbsgears/Contract_Management_Backend/commit/fb0e70bb80ba7eedbc70d1f7c4a242a32bc9f3d7))
* **contracts:** Contract | Create Retention | Overall Retention[CM-14] ([#27](https://github.com/pbsgears/Contract_Management_Backend/issues/27)) ([bc36e05](https://github.com/pbsgears/Contract_Management_Backend/commit/bc36e052805b4d2a913645260eb14aa44849c6b0))
* **contracts:** Contract | Create Retention | Overall Retention[CM-14] ([#27](https://github.com/pbsgears/Contract_Management_Backend/issues/27)) ([2781c88](https://github.com/pbsgears/Contract_Management_Backend/commit/2781c889e99de220f464854b5eca989fc5256685))
* **Contracts:** contract attach document module [CM-25] ([#44](https://github.com/pbsgears/Contract_Management_Backend/issues/44)) ([7481f82](https://github.com/pbsgears/Contract_Management_Backend/commit/7481f820bebc1e5d661fb5474f55088af7e45a2a))
* **Contracts:** contract attach document module [CM-25] ([#44](https://github.com/pbsgears/Contract_Management_Backend/issues/44)) ([dd6ad64](https://github.com/pbsgears/Contract_Management_Backend/commit/dd6ad64c93d56e9f8c4f838e43aafd7e88151d8c))
* **contracts:** Contract Confirmation & Approval [CM-90] ([#42](https://github.com/pbsgears/Contract_Management_Backend/issues/42)) ([482aa92](https://github.com/pbsgears/Contract_Management_Backend/commit/482aa92cdfed92b81c9a1287f0d683979c2b3e0d))
* **contracts:** Contract Confirmation & Approval [CM-90] ([#42](https://github.com/pbsgears/Contract_Management_Backend/issues/42)) ([25f1cec](https://github.com/pbsgears/Contract_Management_Backend/commit/25f1cec96b2dd556229a845959d1f72de604578c))
* **contracts:** contract create header information [CM-5] ([#18](https://github.com/pbsgears/Contract_Management_Backend/issues/18)) ([a708b45](https://github.com/pbsgears/Contract_Management_Backend/commit/a708b45d202f3f837b2414a72ed3106659f41bc2))
* **contracts:** contract create header information [CM-5] ([#18](https://github.com/pbsgears/Contract_Management_Backend/issues/18)) ([ce4c8a0](https://github.com/pbsgears/Contract_Management_Backend/commit/ce4c8a09bfb6d77329e1b9f6c11be6dd461b1813))
* **contracts:** Contract Info edit [CM-8] ([#19](https://github.com/pbsgears/Contract_Management_Backend/issues/19)) ([c30d817](https://github.com/pbsgears/Contract_Management_Backend/commit/c30d817f3a9b5141b294351764ffa54f6cb93ca3))
* **contracts:** Contract Info edit [CM-8] ([#19](https://github.com/pbsgears/Contract_Management_Backend/issues/19)) ([dd3e312](https://github.com/pbsgears/Contract_Management_Backend/commit/dd3e31234408f228197f9da9587f238cb34b2bce))
* **contracts:** contract view page [CM-4] ([#10](https://github.com/pbsgears/Contract_Management_Backend/issues/10)) ([eea0511](https://github.com/pbsgears/Contract_Management_Backend/commit/eea0511e9401ff5fa21156028f8a76732082382f))
* **contracts:** contract view page [CM-4] ([#10](https://github.com/pbsgears/Contract_Management_Backend/issues/10)) ([8dff688](https://github.com/pbsgears/Contract_Management_Backend/commit/8dff68879494bc2f8a0fcd1353d65bb93651ba1a))
* **contracts:** edit contract settings [CM-7] ([#20](https://github.com/pbsgears/Contract_Management_Backend/issues/20)) ([dccc963](https://github.com/pbsgears/Contract_Management_Backend/commit/dccc963bd6102e65e1e72f43a3fc18ecb80b18ea))
* **contracts:** edit contract settings [CM-7] ([#20](https://github.com/pbsgears/Contract_Management_Backend/issues/20)) ([ebc977c](https://github.com/pbsgears/Contract_Management_Backend/commit/ebc977c51ae9618410ea7563cda7fcdd643bba64))
* **contracts:** Enhancement | Contract | Contract Info [CM-289] ([#164](https://github.com/pbsgears/Contract_Management_Backend/issues/164)) ([3f1b49a](https://github.com/pbsgears/Contract_Management_Backend/commit/3f1b49a99350f4dbb91921316b4784b3cf2a06db))
* **contracts:** Enhancement | Contract | Milestone and Deliverables [CM-162] ([#122](https://github.com/pbsgears/Contract_Management_Backend/issues/122)) ([ef258dc](https://github.com/pbsgears/Contract_Management_Backend/commit/ef258dc9214245911accfb1ae380aefb44ccc538))
* **contracts:** Enhancement | Contract | Milestone Retention [CM-169] ([#126](https://github.com/pbsgears/Contract_Management_Backend/issues/126)) ([6da198c](https://github.com/pbsgears/Contract_Management_Backend/commit/6da198c9c3efb2dd99ccaf6061e4b45f32d66a8a))
* **contracts:** Enhancement | Contract | User and User Groups| validation [CM-209] ([#134](https://github.com/pbsgears/Contract_Management_Backend/issues/134)) ([abca3b2](https://github.com/pbsgears/Contract_Management_Backend/commit/abca3b2386fe1499017d42b316711282ace757ee))
* **contracts:** Enhancement | Contract Date Mandatory [CM-167] ([#130](https://github.com/pbsgears/Contract_Management_Backend/issues/130)) ([2b9389d](https://github.com/pbsgears/Contract_Management_Backend/commit/2b9389dfa726eea768cdbe1015dee8837df9d78f))
* **masters:** document master [CM-89] ([#39](https://github.com/pbsgears/Contract_Management_Backend/issues/39)) ([9f3a04b](https://github.com/pbsgears/Contract_Management_Backend/commit/9f3a04be03661540bbd0f550f4ac87aaf800ba6d))
* **masters:** document master [CM-89] ([#39](https://github.com/pbsgears/Contract_Management_Backend/issues/39)) ([000214e](https://github.com/pbsgears/Contract_Management_Backend/commit/000214e56fd9970e83d18d18c6ad63202ca79a97))
* **PMS:** Admin Setting | Contract Type basic config [CM-26] ([#7](https://github.com/pbsgears/Contract_Management_Backend/issues/7)) ([f09e289](https://github.com/pbsgears/Contract_Management_Backend/commit/f09e2897621ad18c9bd18cfd930986126392a206))


### Bug Fixes

* **Amendment:** Display error message when amend a contract (In UAT environment) [CM-303] ([b357014](https://github.com/pbsgears/Contract_Management_Backend/commit/b3570147d85a91b0a909eeb4326ba47f5eef4480))
* **contract:** invalid secondary email validation [CM-168] ([#109](https://github.com/pbsgears/Contract_Management_Backend/issues/109)) ([2677b45](https://github.com/pbsgears/Contract_Management_Backend/commit/2677b45eb2acf9e136fce6935da083ff712affe1))
* **contracts:** Display error message when user try to inactive contract user [CM-298] ([#170](https://github.com/pbsgears/Contract_Management_Backend/issues/170)) ([f13b361](https://github.com/pbsgears/Contract_Management_Backend/commit/f13b3613ce5ea103c3135f0058b84be9ce6de2b5))
* **contracts:** export excel functionality not working UAT [CM-103] ([#49](https://github.com/pbsgears/Contract_Management_Backend/issues/49)) ([7929449](https://github.com/pbsgears/Contract_Management_Backend/commit/7929449303793ddfa78720259774d0336a75ba22))
* **contracts:** export excel functionality not working UAT [CM-103] ([#49](https://github.com/pbsgears/Contract_Management_Backend/issues/49)) ([69460fe](https://github.com/pbsgears/Contract_Management_Backend/commit/69460feb9fe635fd747e4f42b7edbead6d9a34e8))
* **contracts:** Unable to save the 'Contract Info' page with valid date selections for 'Agreement Sign Date' and 'Contract Start Date' [CM-237] ([#136](https://github.com/pbsgears/Contract_Management_Backend/issues/136)) ([512e2d5](https://github.com/pbsgears/Contract_Management_Backend/commit/512e2d54e11ccd961c836d94857795fab375ca32))
* **contracts:** User can confirm the contract when BOQ items' Quantit… ([#156](https://github.com/pbsgears/Contract_Management_Backend/issues/156)) ([3f1417a](https://github.com/pbsgears/Contract_Management_Backend/commit/3f1417afd041fef10d8d5b2ea16cd4d1aaef7858))
