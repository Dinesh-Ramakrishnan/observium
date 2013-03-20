<?php

if (!$os)
{

  // First check the sysObjectID, then the sysDescr
  if (strstr($sysObjectId, "1.3.6.1.4.1.8072.3.2.8") || preg_match('/^FreeBSD/', $sysDescr))
  {
    $os = "freebsd";
  }
  elseif (strstr($sysDescr, "m0n0wall"))
  {
    $os = "monowall"; // m0n0wall, based on FreeBSD
  }
  elseif (strstr($sysDescr, 'FreeBSD'))
  {
    $os = "freebsd";
    // But overwrite if detected more info
    if (strstr($sysObjectId, '1.3.6.1.4.1.12325.1.1.2.1')) // It's all BSNMP daemons
    {
      if (preg_match('/^[^\s]+ \d+ FreeBSD [\d\.-]+RELEASE/', $sysDescr))
      {
        // pfSense - sysDescr.0 = pfsense.localdomain 3255662572 FreeBSD 8.1-RELEASE-p13
        //$os = "pfsense"; ///FIXME. it's horrible, disabled for now.
      }
      else
      {
        $extOutput = snmp_get($device, "UCD-SNMP-MIB::extOutput.0", "-Oqv");
        if (strstr($extOutput, 'nas4free'))
        {
          // NAS4Free - UCD-SNMP-MIB::extOutput.0 = FreeBSD nas.local 9.1-RELEASE FreeBSD 9.1-RELEASE #0 r244224M: Fri Dec 14 19:53:16 JST 2012     aoyama@nas4free.local:/usr/obj/nas4free/usr/src/sys/NAS4FREE-i386  i386
          $os = "nas4free";
        }
        elseif (strstr($extOutput, 'freenas'))
        {
          // FreeNAS - UCD-SNMP-MIB::extOutput.0 = FreeBSD freenas.local 7.3-RELEASE-p3 FreeBSD 7.3-RELEASE-p3 #0: Tue Nov  2 22:41:50 CET 2010     root@dev.freenas.org:/usr/obj/freenas/usr/src/sys/FREENAS-amd64  amd64
          $os = "freenas";
        }
      }
    }
  }
}

?>
